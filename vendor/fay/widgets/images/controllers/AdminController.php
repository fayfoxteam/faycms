<?php
namespace fay\widgets\images\controllers;

use fay\core\Widget;

class AdminController extends Widget{
	public function index($data){
		//获取默认模版
		if(empty($data['template'])){
			$data['template'] = file_get_contents(__DIR__.'/../views/index/template.php');
			$this->form->setData(array(
				'template'=>$data['template'],
			), true);
		}
		
		$this->view->data = $data;
		$this->view->render();
	}
	
	public function onPost(){
		$data = $this->form->getFilteredData();
		
		$photos = $this->input->post('photos', 'intval', array());
		$links = $this->input->post('links', 'trim');
		$titles = $this->input->post('titles', 'trim');
		foreach($photos as $p){
			$data['files'][] = array(
				'file_id'=>$p,
				'link'=>$links[$p],
				'title'=>$titles[$p],
			);
		}
		$this->saveData($data);
		$this->flash->set('编辑成功', 'success');
	}
	
	public function rules(){
		return array();
	}
	
	public function labels(){
		return array();
	}
	
	public function filters(){
		return array(
			'template'=>'trim',
		);
	}
}