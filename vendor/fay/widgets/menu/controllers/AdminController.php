<?php
namespace fay\widgets\menu\controllers;

use fay\core\Widget;
use fay\models\Menu;
use fay\models\tables\Menus;

class AdminController extends Widget{
	
	public $title = '导航菜单';
	public $author = 'fayfox';
	public $author_link = 'http://www.fayfox.com';
	public $description = '以ul, li的方式渲染一个导航树';
	
	public function index($data){
		$this->view->menu = array(
			array(
				'id'=>Menus::ITEM_USER_MENU,
				'title'=>'顶级',
				'children'=>Menu::model()->getTree(Menus::ITEM_USER_MENU, true, true),
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