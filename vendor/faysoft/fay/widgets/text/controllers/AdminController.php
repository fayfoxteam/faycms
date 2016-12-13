<?php
namespace fay\widgets\text\controllers;

use fay\widget\Widget;
use fay\services\Flash;

class AdminController extends Widget{
	public function index($data){
		$this->view->data = $data;
		$this->view->render();
	}
	
	public function onPost(){
		$this->saveConfig($this->form->getFilteredData());
		Flash::set('编辑成功', 'success');
	}
	
	public function labels(){
		return array(
			'content'=>'文本',
		);
	}
	
	public function filters(){
		return array(
			'content'=>'',
		);
	}
}