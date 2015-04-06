<?php
namespace fay\widgets\category_post\controllers;

use fay\core\Widget;
use fay\models\Category;

class AdminController extends Widget{
	
	public $title = '分类文章';
	public $author = 'fayfox';
	public $author_link = 'http://www.fayfox.com';
	public $description = '显示某个分类下的文章';
	
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
			$data['template'] = file_get_contents(dirname(__FILE__).'/../views/index/template.php');
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
		
		$this->saveData($data);
		
		$this->flash->set('编辑成功', 'success');
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