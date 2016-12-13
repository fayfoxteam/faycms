<?php
namespace fay\widgets\options\controllers;

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
		
		$this->view->config = $config;
		$this->view->render();
	}
	
	public function onPost(){
		$data = $this->form->getFilteredData();
		$keys = $this->input->post('keys', null, array());
		$values = $this->input->post('values', null, array());
		
		$data['data'] = array();
		foreach($keys as $i=>$k){
			$data['data'][] = array(
				'key'=>$k,
				'value'=>isset($values[$i]) ? $values[$i] : '',
			);
		}
		
		if(str_replace("\r", '', $data['template']) == str_replace("\r", '', file_get_contents(__DIR__.'/../views/index/template.php'))){
			$data['template'] = '';
		}
		
		$this->saveConfig($data);
		Flash::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array();
	}
	
	public function labels(){
		return array(
			'title'=>'标题',
			'template'=>'模版',
		);
	}
	
	public function filters(){
		return array(
			'title'=>'trim',
			'template'=>'trim',
		);
	}
}