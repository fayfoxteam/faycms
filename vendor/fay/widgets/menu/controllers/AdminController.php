<?php
namespace fay\widgets\menu\controllers;

use fay\core\Widget;
use fay\models\Menu;
use fay\models\tables\Menus;
use fay\models\Flash;

class AdminController extends Widget{
	public function index($config){
		$this->view->menu = array(
			array(
				'id'=>Menus::ITEM_USER_MENU,
				'title'=>'顶级',
				'children'=>Menu::model()->getTree(Menus::ITEM_USER_MENU, true, true),
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
			'hierarchical'=>$this->input->post('hierarchical', 'intval', 0),
			'top'=>$this->input->post('top', 'intval', 0),
			'title'=>$this->input->post('title', null, ''),
			'uri'=>$uri,
			'template'=>$template,
		));
		Flash::set('编辑成功', 'success');
	}
	
}