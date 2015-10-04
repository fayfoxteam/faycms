<?php
namespace fay\widgets\options\controllers;

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
		$keys = $this->input->post('keys', null, array());
		$values = $this->input->post('values', null, array());
		$data = array(
			'data'=>array(),
			'template'=>$this->input->post('template'),
		);
		
		foreach($keys as $i=>$k){
			$data['data'][] = array(
				'key'=>$k,
				'value'=>isset($values[$i]) ? $values[$i] : '',
			);
		}
		
		if(str_replace("\r", '', $data['template']) == str_replace("\r", '', file_get_contents(__DIR__.'/../views/index/template.php'))){
			$data['template'] = '';
		}
		
		$this->setConfig($data);
		Flash::set('编辑成功', 'success');
	}
}