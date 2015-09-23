<?php
namespace fay\widgets\images\controllers;

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
		$this->setConfig($data);
		Flash::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array(array('width', 'height'), 'int', array('min'=>1)),
		);
	}
	
	public function labels(){
		return array(
			'template'=>'模版',
			'width'=>'图片宽度',
			'height'=>'图片高度',
		);
	}
	
	public function filters(){
		return array(
			'template'=>'trim',
			'width'=>'intval',
			'height'=>'intval',
		);
	}
}