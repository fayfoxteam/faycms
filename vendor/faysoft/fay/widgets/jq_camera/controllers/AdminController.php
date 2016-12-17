<?php
namespace fay\widgets\jq_camera\controllers;

use fay\widget\Widget;
use fay\services\Flash;

class AdminController extends Widget{
	public function initConfig($config){
		empty($config['files']) && $config['files'] = array();
		isset($config['height']) || $config['height'] = 450;
		isset($config['transPeriod']) || $config['transPeriod'] = 800;
		isset($config['time']) || $config['time'] = 5000;
		isset($config['fx']) || $config['fx'] = 'random';
		
		//设置模版
		empty($config['template']) && $config['template'] = $this->getDefaultTemplate();
		$this->form->setData(array(
			'template'=>$config['template'],
		), true);
		
		return $this->config = $config;
	}
	
	public function index(){
		$this->view->render();
	}
	
	public function onPost(){
		$data = $this->form->getFilteredData();
		
		$files = $this->input->post('files', 'intval', array());
		$links = $this->input->post('links', 'trim');
		$titles = $this->input->post('titles', 'trim');
		$start_times = $this->input->post('start_time', 'trim|strtotime');
		$end_times = $this->input->post('end_time', 'trim|strtotime');
		foreach($files as $p){
			$data['files'][] = array(
				'file_id'=>$p,
				'link'=>$links[$p],
				'title'=>$titles[$p],
				'start_time'=>$start_times[$p] ? $start_times[$p] : 0,
				'end_time'=>$end_times[$p] ? $end_times[$p] : 0,
			);
		}
		
		//若模版与默认模版一致，不保存
		if($this->isDefaultTemplate($data['template'])){
			$data['template'] = '';
		}
		
		$this->saveConfig($data);
		Flash::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array('links', 'url'),
			array(array('transPeriod', 'time'), 'int'),
		);
	}
	
	public function labels(){
		return array(
			'links'=>'链接地址',
			'height'=>'高度',
			'transPeriod'=>'过渡动画时长',
			'time'=>'播放间隔时长',
			'start_time'=>'生效时间',
			'end_time'=>'过期时间',
		);
	}
	
	public function filters(){
		return array(
			'element_id'=>'trim',
			'height'=>'trim',
			'transPeriod'=>'intval',
			'time'=>'intval',
			'fx'=>'trim',
			'template'=>'trim',
		);
	}
}