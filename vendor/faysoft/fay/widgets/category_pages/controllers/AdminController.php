<?php
namespace fay\widgets\category_pages\controllers;

use fay\widget\Widget;
use fay\services\Category;
use fay\services\Flash;

class AdminController extends Widget{
	public function initConfig($config){
		//设置模版
		$this->form->setData(array(
			'template'=>$this->getTemplate(),
		), true);
		
		return $this->config = $config;
	}
	
	public function index(){
		$root_node = Category::service()->getByAlias('_system_page', 'id');
		$this->view->cats = array(
			array(
				'id'=>$root_node['id'],
				'title'=>'顶级',
				'children'=>Category::service()->getTreeByParentId($root_node['id']),
			),
		);
		
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
	
	/**
	 * 当有post提交的时候，会自动调用此方法
	 */
	public function onPost(){
		$data = $this->form->getFilteredData();
		$data['uri'] || $data['uri'] = empty($data['other_uri']) ? 'page/{$id}' : $data['other_uri'];
		//若模版与默认模版一致，不保存
		if($this->isDefaultTemplate($data['template'])){
			$data['template'] = '';
		}
		$this->saveConfig($data);
		
		Flash::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array('number', 'int', array('min'=>1)),
			array('last_view_time', 'int', array('min'=>0)),
		);
	}
	
	public function labels(){
		return array(
			'number'=>'显示文章数',
			'last_view_time'=>'最近访问',
		);
	}
	
	public function filters(){
		return array(
			'subclassification'=>'intval',
			'top'=>'intval',
			'title'=>'trim',
			'show_empty'=>'intval',
			'number'=>'intval',
			'uri'=>'trim',
			'other_uri'=>'trim',
			'template'=>'trim',
			'date_format'=>'trim',
			'thumbnail'=>'intval',
			'last_view_time'=>'intval',
			'order'=>'trim',
		);
	}
}