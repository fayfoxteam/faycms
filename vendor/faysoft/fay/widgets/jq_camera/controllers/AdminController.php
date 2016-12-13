<?php
namespace fay\widgets\jq_camera\controllers;

use fay\widget\Widget;
use fay\services\Flash;

class AdminController extends Widget{
	public function index($config){
		$this->view->config = $config;
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
			'height'=>'trim',
			'transPeriod'=>'intval',
			'time'=>'intval',
			'fx'=>'trim',
		);
	}
}