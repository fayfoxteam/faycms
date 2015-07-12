<?php
namespace fay\widgets\post_item\controllers;

use fay\core\Widget;
use fay\models\Flash;
use fay\models\Post;
use fay\models\tables\Posts;

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
		
		if($config['fixed_id']){
			$post = Posts::model()->find($config['fixed_id'], 'title');
			$this->form->setData(array(
				'fixed_title'=>$post['title'],
			));
		}
		
		$this->view->render();
	}
	
	/**
	 * 当有post提交的时候，会自动调用此方法
	 */
	public function onPost(){
		$data = $this->form->getFilteredData();
		
		if($data['type'] == 'by_id'){
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
			array('fixed_id', 'int', array('min'=>1)),
			array('type', 'range', array('range'=>array('by_id', 'fixed_post'))),
		);
	}
	
	public function labels(){
		return array(
			'fixed_id'=>'固定文章ID',
			'type'=>'显示方式',
		);
	}
	
	public function filters(){
		return array(
			'type'=>'trim',
			'id_key'=>'trim',
			'fixed_id'=>'intval',
			'template'=>'trim',
			'fields'=>'trim',
		);
	}
}