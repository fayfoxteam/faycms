<?php
namespace fay\widgets\widgetarea\controllers;

use fay\helpers\ArrayHelper;
use fay\widget\Widget;
use fay\services\Flash;

class AdminController extends Widget{
	public function index(){
		//获取默认模版
		if(empty($config['template'])){
			$config['template'] = file_get_contents(__DIR__.'/../views/index/template.php');
			$this->form->setData(array(
				'template'=>$config['template'],
			), true);
		}
		
		$this->view->assign(array(
			'widgetareas'=>ArrayHelper::column(\F::config()->getFile('widgetareas'), 'description', 'alias'),
			'config'=>$config
		))->render();
	}
	
	public function onPost(){
		$this->saveConfig($this->form->getFilteredData());
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