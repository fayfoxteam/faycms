<?php
namespace fay\widgets\menu\controllers;

use fay\widget\Widget;
use fay\services\Menu;
use fay\models\tables\Menus;
use fay\services\Flash;

class AdminController extends Widget{
	public function index($config){
		$this->view->menu = array(
			array(
				'id'=>Menus::ITEM_USER_MENU,
				'title'=>'顶级',
				'children'=>Menu::service()->getTree(Menus::ITEM_USER_MENU, true, true),
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
		//若模版与默认模版一致，不保存
		$template = $this->input->post('template');
		if(str_replace("\r", '', $template) == str_replace("\r", '', file_get_contents(__DIR__.'/../views/index/template.php'))){
			$template = '';
		}
		$this->saveConfig(array(
			'top'=>$this->input->post('top', 'intval', 0),
			'template'=>$template,
		));
		Flash::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array(array('top'), 'int', array('min'=>0))
		);
	}
	
	public function labels(){
		return array(
			'top'=>'顶级菜单',
			'template'=>'渲染模版',
		);
	}
	
	public function filters(){
		return array(
			'top'=>'intval',
			'template'=>'trim',
		);
	}
	
}