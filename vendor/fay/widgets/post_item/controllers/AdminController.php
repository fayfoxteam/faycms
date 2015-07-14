<?php
namespace fay\widgets\post_item\controllers;

use fay\core\Widget;
use fay\models\Flash;
use fay\models\tables\Posts;
use fay\models\Category;

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
		
		if(!empty($config['fixed_id'])){
			$post = Posts::model()->find($config['fixed_id'], 'title');
			$this->form->setData(array(
				'fixed_title'=>$post['title'],
			));
		}
		
		//所有分类
		$root_node = Category::model()->getByAlias('_system_post', 'id');
		$this->view->cats = array(
			array(
				'id'=>$root_node['id'],
				'title'=>'顶级',
				'children'=>Category::model()->getTreeByParentId($root_node['id']),
			),
		);
		
		$this->view->render();
	}
	
	/**
	 * 当有post提交的时候，会自动调用此方法
	 */
	public function onPost(){
		$data = $this->form->getFilteredData();
		
		if($data['type'] == 'by_input'){
			$data['fixed_id'] = '';
		}else{
			$data['id_key'] = '';
		}
		
		//若模版与默认模版一致，不保存
		if(str_replace("\r", '', $data['template']) == str_replace("\r", '', file_get_contents(__DIR__.'/../views/index/template.php'))){
			$data['template'] = '';
		}
		
		$this->saveData($data);
		
		Flash::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array(array('fixed_id', 'under_cat_id'), 'int', array('min'=>1)),
			array('type', 'range', array('range'=>array('by_input', 'fixed_post'))),
			array('inc_views', 'range', array('range'=>array('0', '1'))),
			array('under_cat_id', 'exist', array('table'=>'categories', 'field'=>'id')),
			array('fixed_id', 'exist', array('table'=>'posts', 'field'=>'id')),
		);
	}
	
	public function labels(){
		return array(
			'fixed_id'=>'固定文章',
			'type'=>'显示方式',
			'under_cat_id'=>'所属分类',
		);
	}
	
	public function filters(){
		return array(
			'type'=>'trim',
			'id_key'=>'trim',
			'fixed_id'=>'intval',
			'template'=>'trim',
			'fields'=>'trim',
			'under_cat_id'=>'intval',
			'inc_views'=>'intval',
		);
	}
}
