<?php
namespace fay\widgets\widgetarea\controllers;

use fay\helpers\ArrayHelper;
use fay\widget\Widget;
use fay\services\Flash;

class AdminController extends Widget{
	public function initConfig($config){
		//设置模版
		empty($config['template']) && $config['template'] = $this->getDefaultTemplate();
		
		return $this->config = $config;
	}
	
	public function index(){
		$this->view->assign(array(
			'widgetareas'=>ArrayHelper::column(\F::config()->getFile('widgetareas'), 'description', 'alias'),
		))->render();
	}
	
	public function onPost(){
		$data = $this->form->getFilteredData();
		
		//若模版与默认模版一致，不保存
		if($this->isDefaultTemplate($data['template'])){
			$data['template'] = '';
		}
		
		$this->saveConfig($data);
		Flash::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array(array('alias'), 'required'),
		);
	}
	
	public function labels(){
		return array(
			'alias'=>'小工具域',
		);
	}
	
	public function filters(){
		return array(
			'alias'=>'trim',
			'template'=>'trim',
		);
	}
}