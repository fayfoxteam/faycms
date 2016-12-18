<?php
namespace fay\widgets\tags\controllers;

use fay\widget\Widget;
use fay\services\Category;
use fay\services\Flash;

class AdminController extends Widget{
	public function initConfig($config){
		empty($config['number']) && $config['number'] = 10;
		empty($config['uri']) && $config['uri'] = 'tag/{$title}';
		empty($config['title']) && $config['title'] = '';
		
		//设置模版
		empty($config['template']) && $config['template'] = $this->getDefaultTemplate();
		
		return $this->config = $config;
	}
	
	public function index(){
		$this->view->render();
	}
	
	/**
	 * 当有post提交的时候，会自动调用此方法
	 */
	public function onPost(){
		$data = $this->form->getFilteredData();
		$data['uri'] || $data['uri'] = $this->input->post('other_uri');
		
		//若模版与默认模版一致，不保存
		if($this->isDefaultTemplate($data['template'])){
			$data['template'] = '';
		}
		
		$this->saveConfig($data);
		Flash::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array(array('number'), 'int', array('min'=>1))
		);
	}
	
	public function labels(){
		return array(
			'title'=>'标题',
			'number'=>'数量',
			'uri'=>'链接格式',
			'template'=>'渲染模版',
			'order'=>'排序方式',
		);
	}
	
	public function filters(){
		return array(
			'title'=>'trim',
			'number'=>'intval',
			'uri'=>'trim',
			'template'=>'trim',
			'order'=>'trim',
		);
	}
}