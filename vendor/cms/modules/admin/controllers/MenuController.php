<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\Menu;
use fay\models\tables\Menus;
use fay\models\tables\Actionlogs;
use fay\core\Response;

class MenuController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'menu';
	}
	
	public function index(){
		$this->layout->subtitle = '导航栏';
		$this->view->menus = Menu::model()->getTree(null, false);
		if(\F::app()->checkPermission('admin/menu/create')){
			$this->layout->sublink = array(
				'uri'=>'#create-cat-dialog',
				'text'=>'添加菜单集',
				'htmlOptions'=>array(
					'class'=>'create-cat-link',
					'data-title'=>'菜单集',
					'data-id'=>0,
				),
			);
		}
		$this->view->render();
	}
	
	public function create(){
		$this->form()->setModel(Menus::model());
		if($this->input->post()){
			if($this->form()->check()){
				$data = $this->form()->getFilteredData();
				
				$parent = $this->input->post('parent', 'intval', 0);
				$sort = $this->input->post('sort', 'intval', 100);
				
				$menu_id = Menu::model()->create($parent, $sort, $data);
				
				$this->actionlog(Actionlogs::TYPE_MENU, '添加菜单', $menu_id);
				
				$menu = Menus::model()->find($menu_id);
				Response::output('success', array(
					'data'=>$menu,
					'message'=>"菜单{$menu['title']}被添加",
				));
			}else{
				Response::output('error', '参数异常');
			}
		}else{
			Response::output('error', '请提交数据');
		}
	}
	
	public function remove(){
		$id = $this->input->get('id', 'intval');
		if(Menu::model()->remove($id)){
			$this->actionlog(Actionlogs::TYPE_MENU, '移除导航', $id);
				
			Response::output('success', array(
				'message'=>'一个菜单被删除',
			));
		}else{
			Response::output('error', '菜单不存在或已被删除');
		}
	}
	
	public function removeAll(){
		$id = $this->input->get('id', 'intval');
		if(Menu::model()->removeAll($id)){
			$this->actionlog(Actionlogs::TYPE_MENU, '移除导航及其所有子节点', $id);
		
			Response::output('success', array(
				'message'=>'一个菜单组被删除',
			));
		}else{
			Response::output('error', '删除失败');
		}
	}
	
	public function edit(){
		if($this->input->post()){
			if($this->form()->setModel(Menus::model())->check()){
				$id = $this->input->post('id', 'intval');
				$data = $this->form()->getFilteredData();
				
				$parent = $this->input->post('parent', 'intval', null);
				$sort = $this->input->post('sort', 'intval', null);
					
				Menu::model()->update($id, $data, $sort, $parent);
				
				$this->actionlog(Actionlogs::TYPE_MENU, '编辑了菜单', $id);
				
				$node = Menus::model()->find($id);
				Response::output('success', array(
					'message'=>"菜单{$node['title']}被编辑",
					'data'=>$node,
				));
			}else{
				Response::output('error', '参数异常');
			}
		}else{
			Response::output('error', '请提交数据');
		}
	}
	
	public function sort(){
		$id = $this->input->get('id', 'intval');
		Menu::model()->sort($id, $this->input->get('sort', 'intval'));
		$this->actionlog(Actionlogs::TYPE_MENU, '改变了菜单排序', $id);
		
		$node = Menus::model()->find($id, 'sort,title');
		Response::output('success', array(
			'message'=>"菜单{$node['title']}的排序值被修改",
			'sort'=>$node['sort'],
		));
	}
	
	/**
	 * 获取一条记录
	 */
	public function get(){
		$menu = Menus::model()->find($this->input->get('id', 'intval'));
		$children = Menus::model()->fetchCol('id', array(
			'left_value > '.$menu['left_value'],
			'right_value < '.$menu['right_value'],
		));
		echo json_encode(array(
			'status'=>1,
			'data'=>$menu,
			'children'=>$children,
		));
	}
	
	/**
	 * 索引全表
	 */
	public function reindex(){
		Menu::model()->buildIndex();
		$this->view->render();
	}
}