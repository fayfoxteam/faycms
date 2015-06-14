<?php
namespace fay\widgets\listing\controllers;

use fay\core\Widget;

class AdminController extends Widget{
	public $title = '列表';
	public $author = 'fayfox';
	public $author_link = 'http://www.fayfox.com';
	public $description = '存储一个一维的列表，并通过设定的模板进行渲染。';
	
	public function index($data){
		//帮助面板
		\F::app()->layout->_help_contet = $this->view->render('_help', array(), true);
		
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