<?php
namespace fay\widgets\contact\controllers;

use fay\widget\Widget;
use fay\services\Flash;

class AdminController extends Widget{
	public function index($config){
		$this->view->assign(array(
			'config'=>$config
		))->render();
	}
	
	public function onPost(){
		$this->setConfig($this->form->getFilteredData());
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
		);
	}
}