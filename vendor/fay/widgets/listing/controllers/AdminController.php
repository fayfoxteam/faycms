<?php
namespace fay\widgets\listing\controllers;

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
		
		$values = $this->input->post('data', null, array());
		$data['data'] = array();
		foreach($values as $v){
			$data['data'][] = $v;
		}
		
		if(str_replace("\r", '', $data['template']) == str_replace("\r", '', file_get_contents(__DIR__.'/../views/index/template.php'))){
			$data['template'] = '';
		}
		
		$this->setConfig($data);
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