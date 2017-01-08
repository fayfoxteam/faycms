<?php
namespace fay\widgets\post_list\controllers;

use fay\widget\Widget;
use fay\services\CategoryService;
use fay\services\FlashService;

class AdminController extends Widget{
	public function initConfig($config){
		//设置模版
		empty($config['template']) && $config['template'] = $this->getDefaultTemplate();
		
		return $this->config = $config;
	}
	
	public function index(){
		$root_node = CategoryService::service()->getByAlias('_system_post', 'id');
		$this->view->assign(array(
			'cats'=>array(
				array(
					'id'=>$root_node['id'],
					'title'=>'顶级',
					'children'=>CategoryService::service()->getTreeByParentId($root_node['id']),
				),
			)
		));
		
		$this->view->render();
	}
	
	/**
	 * 当有post提交的时候，会自动调用此方法
	 */
	public function onPost(){
		$data = $this->form->getFilteredData();
		$data['uri'] || $data['uri'] = empty($data['other_uri']) ? 'post/{$id}' : $data['other_uri'];
		
		//若模版与默认模版一致，不保存
		if($this->isDefaultTemplate($data['template'])){
			$data['template'] = '';
		}
		if(empty($data['fields'])){
			$data['fields'] = array();
		}
		$this->saveConfig($data);
		
		FlashService::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array('page_size', 'int', array('min'=>1)),
			array(array('file_thumbnail_width', 'file_thumbnail_height', 'post_thumbnail_width', 'post_thumbnail_height'), 'int', array('min'=>0)),
			array('pager', 'range', array('range'=>array('system', 'custom'))),
			array('cat_id', 'exist', array('table'=>'categories', 'field'=>'id')),
		);
	}
	
	public function labels(){
		return array(
			'page_size'=>'分页大小',
			'page_key'=>'页码字段',
			'cat_key'=>'分类字段',
			'cat_id'=>'默认分类',
			'post_thumbnail_width'=>'文章缩略图宽度',
			'post_thumbnail_height'=>'文章缩略图高度',
			'file_thumbnail_width'=>'附件缩略图宽度',
			'file_thumbnail_height'=>'附件缩略图高度',
		);
	}
	
	public function filters(){
		return array(
			'page_size'=>'intval',
			'page_key'=>'trim',
			'cat_key'=>'trim',
			'order'=>'trim',
			'uri'=>'trim',
			'other_uri'=>'trim',
			'date_format'=>'trim',
			'template'=>'trim',
			'fields'=>'trim',
			'pager'=>'trim',
			'pager_template'=>'trim',
			'empty_text'=>'trim',
			'cat_id'=>'intval',
			'subclassification'=>'intval',
			'post_thumbnail_width'=>'intval',
			'post_thumbnail_height'=>'intval',
			'file_thumbnail_width'=>'intval',
			'file_thumbnail_height'=>'intval',
		);
	}
}