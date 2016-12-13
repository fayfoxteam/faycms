<?php
namespace fay\widgets\tags\controllers;

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
		$data = $this->form()->getFilteredData();
		$data['uri'] || $data['uri'] = $this->input->post('other_uri');
		
		//若模版与默认模版一致，不保存
		if(str_replace("\r", '', $data['template']) == str_replace("\r", '', file_get_contents(__DIR__.'/../views/index/template.php'))){
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
		);
	}
	
	public function filters(){
		return array(
			'title'=>'trim',
			'number'=>'intval',
			'uri'=>'trim',
			'template'=>'trim',
		);
	}
}