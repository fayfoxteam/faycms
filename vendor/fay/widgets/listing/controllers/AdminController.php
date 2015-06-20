<?php
namespace fay\widgets\listing\controllers;

use fay\core\Widget;

class AdminController extends Widget{
	public function index($data){
		$this->view->data = $data;
		$this->view->render();
	}
	
	public function onPost(){
		$keys = $this->input->post('keys', null, array());
		$values = $this->input->post('values', null, array());
		$data = array(
			'values'=>array(),
			'template'=>$this->input->post('template'),
		);
		
		foreach($values as $v){
			$data['values'][] = $v;
		}
		$this->saveData($data);
		$this->flash->set('编辑成功', 'success');
	}
}