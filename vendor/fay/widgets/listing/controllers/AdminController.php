<?php
namespace fay\widgets\listing\controllers;

use fay\core\Widget;
use fay\models\Flash;

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
		$values = $this->input->post('values', null, array());
		$data = array(
			'values'=>array(),
			'template'=>$this->input->post('template'),
		);
		
		foreach($values as $v){
			$data['values'][] = $v;
		}
		
		if(str_replace("\r", '', $data['template']) == str_replace("\r", '', file_get_contents(__DIR__.'/../views/index/template.php'))){
			$data['template'] = '';
		}
		
		$this->setConfig($data);
		Flash::set('编辑成功', 'success');
	}
}