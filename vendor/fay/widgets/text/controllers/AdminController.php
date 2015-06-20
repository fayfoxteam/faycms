<?php
namespace fay\widgets\text\controllers;

use fay\core\Widget;

class AdminController extends Widget{
	public function index($data){
		$this->view->data = $data;
		$this->view->render();
	}
	
	public function onPost(){
		$this->saveData(array(
			'content'=>$this->input->post('content'),
		));
		$this->flash->set('编辑成功', 'success');
	}
}