<?php
namespace fay\widgets\categories\controllers;

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
		$this->view->cats = array(
			array(
				'id'=>$root_node['id'],
				'title'=>'顶级',
				'children'=>CategoryService::service()->getTreeByParentId($root_node['id']),
			),
		);
		
		$this->view->render();
	}
	
	/**
	 * 当有post提交的时候，会自动调用此方法
	 */
	public function onPost(){
		$data = $this->form->getFilteredData();
		$data['uri'] || $data['uri'] = $this->input->post('other_uri');
		
		//若模版与默认模版一致，不保存
		if($this->isDefaultTemplate($data['template'])){
			$data['template'] = '';
		}
		
		$this->saveConfig($data);
		FlashService::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array('hierarchical', 'range', array('range'=>array('0', '1'))),
			array('top', 'int', array('min'=>0, 'max'=>16777215)),
		);
	}
	
	public function labels(){
		return array(
			'hierarchical'=>'是否体现层级关系',
			'top'=>'顶级分类',
			'title'=>'标题',
			'uri'=>'链接格式',
			'template'=>'渲染模版',
		);
	}
	
	public function filters(){
		return array(
			'hierarchical'=>'intval',
			'top'=>'intval',
			'title'=>'',
			'uri'=>'trim',
			'template'=>'trim',
		);
	}
}