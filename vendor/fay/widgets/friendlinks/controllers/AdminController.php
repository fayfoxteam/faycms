<?php
namespace fay\widgets\friendlinks\controllers;

use fay\core\Widget;
use fay\models\Category;

class AdminController extends Widget{
	
	public $title = '友情链接';
	public $author = 'fayfox';
	public $author_link = 'http://www.fayfox.com';
	public $description = '友情链接列表';
	
	public function index($data){
		//帮助面板
		\F::app()->layout->_help_contet = $this->view->render('_help', array(), true);
		
		$root_node = Category::model()->getByAlias('_system_link', 'id');
		$this->view->cats = array(
			array(
				'id'=>0,
				'title'=>'不限制分类',
				'children'=>Category::model()->getTreeByParentId($root_node['id']),
			),
		);
		
		//获取默认模版
		if(empty($data['template'])){
			$data['template'] = file_get_contents(dirname(__FILE__).'/../views/index/template.php');
		}
		
		$this->view->data = $data;
		
		$this->view->render();
	}
	
	/**
	 * 当有post提交的时候，会自动调用此方法
	 */
	public function onPost(){
		$uri = 'cat/{$id}';
		if($this->input->post('uri')){
			$uri = $this->input->post('uri');
		}else if($this->input->post('other_uri')){
			$uri = $this->input->post('other_uri');
		}
		//若模版与默认模版一致，不保存
		$template = $this->input->post('template');
		if($template == file_get_contents(dirname(__FILE__).'/../views/index/template.php')){
			$template = '';
		}
		$this->saveData(array(
			'title'=>$this->input->post('title', null, ''),
			'number'=>$this->input->post('number', 'intval', 5),
			'cat_id'=>$this->input->post('cat_id', 'intval', 0),
			'template'=>$template,
		));
		$this->flash->set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array('number', 'int', array('min'=>1, 'max'=>20)),
		);
	}
	
	public function labels(){
		return array(
			'number'=>'显示链接数',
		);
	}
	
	public function filters(){
		return array(
			'title'=>'',
			'number'=>'intval',
			'template'=>'trim',
		);
	}
}