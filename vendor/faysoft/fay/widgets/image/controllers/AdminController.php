<?php
namespace fay\widgets\image\controllers;

use fay\widget\Widget;
use fay\services\Flash;

class AdminController extends Widget{
	public function index($config){
		//获取默认模版
		if(empty($config['template'])){
			$config['template'] = file_get_contents(__DIR__.'/../views/index/template.php');
			$this->form->setData(array(
				'template'=>$config['template'],
			), true);
		}
		
		isset($config['file_id']) || $config['file_id'] = 0;
		$this->view->config = $config;
		$this->view->render();
	}
	
	/**
	 * 当有post提交的时候，会自动调用此方法
	 */
	public function onPost(){
		$this->setConfig($this->form->getFilteredData());
		Flash::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array(array('file_id', 'width', 'height'), 'int'),
			array('link', 'url'),
		);
	}
	
	public function labels(){
		return array(
			'width'=>'宽',
			'height'=>'高',
			'link'=>'链接地址',
		);
	}
	
	public function filters(){
		return array(
			'file_id'=>'intval',
			'width'=>'intval',
			'height'=>'intval',
			'link'=>'trim',
			'target'=>'trim',
			'template'=>'trim',
		);
	}
}