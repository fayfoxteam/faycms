<?php
namespace fay\widgets\friendlinks\controllers;

use fay\widget\Widget;
use fay\models\Category;
use fay\models\Flash;

class AdminController extends Widget{
	public function index($config){
		$root_node = Category::model()->getByAlias('_system_link', 'id');
		$this->view->cats = array(
			array(
				'id'=>0,
				'title'=>'不限制分类',
				'children'=>Category::model()->getTreeByParentId($root_node['id']),
			),
		);
		
		//获取默认模版
		if(empty($config['template'])){
			$config['template'] = file_get_contents(__DIR__.'/../views/index/template.php');
		}
		
		$this->view->config = $config;
		
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
		if(str_replace("\r", '', $template) == str_replace("\r", '', file_get_contents(__DIR__.'/../views/index/template.php'))){
			$template = '';
		}
		$this->setConfig(array(
			'title'=>$this->input->post('title', null, ''),
			'number'=>$this->input->post('number', 'intval', 5),
			'cat_id'=>$this->input->post('cat_id', 'intval', 0),
			'template'=>$template,
		));
		Flash::set('编辑成功', 'success');
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