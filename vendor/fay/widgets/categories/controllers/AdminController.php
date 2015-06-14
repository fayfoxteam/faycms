<?php
namespace fay\widgets\categories\controllers;

use fay\core\Widget;
use fay\models\Category;

class AdminController extends Widget{
	
	public $title = '分类目录';
	public $author = 'fayfox';
	public $author_link = 'http://www.fayfox.com';
	public $description = '分类目录的列表';
	
	public function index($data){
		//帮助面板
		\F::app()->layout->_help_contet = $this->view->render('_help', array(), true);
		
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
		$uri = 'cat/{$id}';
		if($this->input->post('uri')){
			$uri = $this->input->post('uri');
		}else if($this->input->post('other_uri')){
			$uri = $this->input->post('other_uri');
		}
		$this->saveData(array(
			'hierarchical'=>$this->input->post('hierarchical', 'intval', 0),
			'top'=>$this->input->post('top', 'intval', 0),
			'title'=>$this->input->post('title', null, ''),
			'uri'=>$uri,
			'template'=>$this->input->post('template'),
		));
		$this->flash->set('编辑成功', 'success');
	}
	
}