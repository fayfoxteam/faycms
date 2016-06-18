<?php
namespace fay\widgets\jq_nivo_slider\controllers;

use fay\widget\Widget;
use fay\models\Flash;

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
		
		$this->setConfig($data);
		Flash::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array('links', 'url'),
			array(array('animSpeed', 'pauseTime', 'width', 'height'), 'int', array('min'=>1)),
			array('element_id', 'string', array('format'=>'alias')),
		);
	}
	
	public function labels(){
		return array(
			'element_id'=>'外层元素ID',
			'links'=>'链接地址',
			'pauseTime'=>'停顿时长',
			'animSpeed'=>'过渡动画时长',
			'width'=>'图片宽度',
			'height'=>'图片高度',
			'start_time'=>'生效时间',
			'end_time'=>'过期时间',
		);
	}
	
	public function filters(){
		return array(
			'animSpeed'=>'intval',
			'pauseTime'=>'intval',
			'effect'=>'trim',
			'element_id'=>'trim',
			'directionNav'=>'intval',
			'width'=>'intval',
			'height'=>'intval',
		);
	}
}