<?php
namespace fay\widgets\images\controllers;

use fay\widget\Widget;
use fay\services\Flash;

class AdminController extends Widget{
	public function index($config){
		//获取默认模版
		if(empty($config['template'])){
			$config['template'] = file_get_contents(__DIR__.'/../views/index/template.php');
			$this->form->setData(array(
				'template'=>$config['template'],
			), true);
		}
		
		$this->view->assign(array(
			'config'=>$config,
		))->render();
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
			array(array('width', 'height'), 'int', array('min'=>1)),
			array(array('start_time', 'end_time'), 'datetime'),
			array('links', 'url'),
		);
	}
	
	public function labels(){
		return array(
			'title'=>'标题',
			'template'=>'模版',
			'width'=>'图片宽度',
			'height'=>'图片高度',
			'links'=>'链接',
			'start_time'=>'生效时间',
			'end_time'=>'过期时间',
		);
	}
	
	public function filters(){
		return array(
			'title'=>'trim',
			'template'=>'trim',
			'width'=>'intval',
			'height'=>'intval',
			'random'=>'intval',
			'limit'=>'intval',
		);
	}
}