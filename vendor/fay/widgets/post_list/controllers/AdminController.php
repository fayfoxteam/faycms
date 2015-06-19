<?php
namespace fay\widgets\post_list\controllers;

use fay\core\Widget;
use fay\models\Category;

class AdminController extends Widget{
	public function index($data){
		$root_node = Category::model()->getByAlias('_system_post', 'id');
		$this->view->cats = array(
			array(
				'id'=>$root_node['id'],
				'title'=>'顶级',
				'children'=>Category::model()->getTreeByParentId($root_node['id']),
			),
		);
		
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
		$this->saveData($data);
		
		$this->flash->set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array('page_size', 'int', array('min'=>1)),
			array('pager', 'range', array('range'=>array('system', 'custom'))),
		);
	}
	
	public function labels(){
		return array(
			'page_size'=>'分页大小',
			'page_key'=>'页码字段',
			'cat_key'=>'分类字段',
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
		);
	}
}