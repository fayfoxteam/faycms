<?php
namespace fay\widgets\category_posts\controllers;

use fay\widget\Widget;
use fay\services\Category;
use fay\services\Flash;

class AdminController extends Widget{
	public function index($config){
		$root_node = Category::service()->getByAlias('_system_post', 'id');
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
		$data['uri'] || $data['uri'] = empty($data['other_uri']) ? 'post/{$id}' : $data['other_uri'];
		//若模版与默认模版一致，不保存
		if(str_replace("\r", '', $data['template']) == str_replace("\r", '', file_get_contents(__DIR__.'/../views/index/template.php'))){
			$data['template'] = '';
		}
		if(empty($data['fields'])){
			$data['fields'] = array();
		}
		$this->setConfig($data);
		
		Flash::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array('number', 'int', array('min'=>1)),
			array(array('last_view_time', 'post_thumbnail_width', 'post_thumbnail_height'), 'int', array('min'=>0)),
		);
	}
	
	public function labels(){
		return array(
			'number'=>'显示文章数',
			'last_view_time'=>'最近访问',
			'post_thumbnail_width'=>'文章缩略图宽度',
			'post_thumbnail_height'=>'文章缩略图高度',
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
			'fields'=>'trim',
			'post_thumbnail_width'=>'intval',
			'post_thumbnail_height'=>'intval',
		);
	}
}