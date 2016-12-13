<?php
namespace fay\widgets\page_item\controllers;

use fay\widget\Widget;
use fay\services\Flash;
use fay\models\tables\Pages;

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
		
		if(!empty($config['default_page_id'])){
			$post = Pages::model()->find($config['default_page_id'], 'title');
			$this->form->setData(array(
				'page_title'=>$post['title'],
			));
		}
		
		$this->view->render();
	}
	
	/**
	 * 当有post提交的时候，会自动调用此方法
	 */
	public function onPost(){
		$data = $this->form->getFilteredData();
		
		//若模版与默认模版一致，不保存
		if(str_replace("\r", '', $data['template']) == str_replace("\r", '', file_get_contents(__DIR__.'/../views/index/template.php'))){
			$data['template'] = '';
		}
		
		$this->saveConfig($data);
		
		Flash::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array('default_page_id', 'int'),
			array('default_page_id', 'exist', array('table'=>'pages', 'field'=>'id')),
			array('inc_views', 'range', array('range'=>array('0', '1'))),
		);
	}
	
	public function labels(){
		return array(
			'default_page_id'=>'固定页面ID',
			'type'=>'显示方式',
		);
	}
	
	public function filters(){
		return array(
			'type'=>'trim',
			'id_key'=>'trim',
			'alias_key'=>'trim',
			'default_page_id'=>'intval',
			'template'=>'trim',
			'fields'=>'trim',
			'inc_views'=>'intval',
		);
	}
}