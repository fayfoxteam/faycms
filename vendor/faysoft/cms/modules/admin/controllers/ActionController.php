<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\services\Category;
use fay\models\tables\Actions;
use fay\models\tables\Actionlogs;
use fay\core\Sql;
use fay\common\ListView;
use fay\core\Response;
use fay\services\Flash;

class ActionController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'role';
	}
	
	public function index(){
		$this->layout->subtitle = '添加权限';
		Flash::set('如果您不清楚它的是干嘛用的，请不要随意修改，后果可能很严重！', 'warning');
		
		$this->_setListview();

		$this->view->cats = Category::service()->getTree('_system_action');
		$this->form()->setModel(Actions::model())
			->setRule(array('parent_router', 'ajax', array('url'=>array('admin/action/is-router-exist'))))
			->setLabels(array('parent_router'=>'父级路由'))
		;
		$this->view->render();
	}
	
	public function create(){
		if($this->input->post()){
			if($this->form()->setModel(Actions::model())
				->setRule(array(array('parent_router',), 'exist', array('table'=>'actions', 'field'=>'router')))
				->setLabels(array('parent_router'=>'父级路由'))
				->check()){
				if($this->input->post('parent_router')){
					$parent_router = Actions::model()->fetchRow(array(
						'router = ?'=>$this->input->post('parent_router', 'trim'),
					), 'id');
					if(!$parent_router){
						Response::notify('error', '父级路由不存在');
					}
					$parent = $parent_router['id'];
				}else{
					$parent = 0;
				}
				$data = $this->form()->getFilteredData();
				$data['parent'] = $parent;
				$result = Actions::model()->insert($data);
				$this->actionlog(Actionlogs::TYPE_ACTION, '添加权限', $result);
				Response::notify('success', '权限添加成功');
			}
		}else{
			Response::notify('error', '不完整的请求');
		}
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑权限';
		$gets = $this->input->get();
		unset($gets['id']);
		$this->layout->sublink = array(
			'uri'=>array('admin/action/index', $gets),
			'text'=>'添加权限',
		);
		$action_id = intval($this->input->get('id', 'intval'));
		$this->view->cats = Category::service()->getNextLevel('_system_action');
		
		$this->form()->setModel(Actions::model())
			->setRule(array(array('parent_router',), 'exist', array('table'=>'actions', 'field'=>'router', 'ajax'=>array('admin/action/is-router-exist'))))
			->setLabels(array('parent_router'=>'父级路由'));
		
		if($this->input->post()){
			if($this->form()->check()){
				if($this->input->post('parent_router')){
					$parent_router = Actions::model()->fetchRow(array(
						'router = ?'=>$this->input->post('parent_router'),
					), 'id');
					if(!$parent_router){
						die('父级路由不存在');
					}
					$parent = $parent_router['id'];
				}else{
					$parent = 0;
				}
				$data = $this->form()->getFilteredData();
				$data['parent'] = $parent;
				isset($data['is_public']) || $data['is_public'] = 0;
				Actions::model()->update($data, "id = {$action_id}");
				$this->actionlog(Actionlogs::TYPE_ACTION, '编辑管理员权限', $action_id);
				Flash::set('权限编辑成功', 'success');
			}
		}

		$action = Actions::model()->find($action_id);
		if($action['parent']){
			$parent_action = Actions::model()->find($action['parent'], 'router');
			$action['parent_router'] = $parent_action['router'];
		}
		$this->form()->setData($action);
		
		$this->_setListview();
		$this->view->render();
	}
	
	public function remove(){
		Actions::model()->delete(array('id = ?'=>$this->input->get('id', 'intval')));
		$this->actionlog(Actionlogs::TYPE_ACTION, '删除权限', $this->input->get('id', 'intval'));
		
		Response::notify('success', '一个权限被删除', $this->view->url('admin/action/index', $this->input->get()));
	}
	
	public function search(){
		$actions = Actions::model()->fetchAll(array(
			'router LIKE ?'=>'%'.$this->input->get('key', false).'%'
		), 'id,router AS title', 'title', 20);
		
		Response::json($actions);
	}
	
	public function isRouterNotExist(){
		if(Actions::model()->fetchRow(array(
			'router = ?'=>$this->input->request('router', 'trim'),
			'id != ?'=>$this->input->request('id', 'intval', false),
		))){
			Response::json('', 0, '该路由已存在');
		}else{
			Response::json('', 1, '路由不存在');
		}
	}
	
	public function isRouterExist(){
		if(Actions::model()->fetchRow(array(
			'router = ?'=>$this->input->request('router', 'trim'),
		))){
			Response::json('', 1, '路由已存在');
		}else{
			Response::json('', 0, '路由不存在');
		}
	}
	
	/**
	 * 设置右侧列表
	 */
	private function _setListview(){
		$sql = new Sql();
		$sql->from(array('a'=>'actions'))
			->joinLeft(array('c'=>'categories'), 'a.cat_id = c.id', 'title AS cat_title')
			->joinLeft(array('pa'=>'actions'), 'a.parent = pa.id', 'router AS parent_router,title AS parent_title')
			->joinLeft(array('pc'=>'categories'), 'pa.cat_id = pc.id', 'title AS parent_cat_title')
			->order('a.cat_id');
		if($this->input->get('cat_id')){
			$sql->where(array(
				'a.cat_id = ?'=>$this->input->get('cat_id', 'intval'),
			));
		}
		
		if($this->input->get('router')){
			$sql->where(array(
				'a.router LIKE ?'=>$this->input->get('router').'%',
			));
		}
		$this->view->listview = new ListView($sql);
	}

	public function cat(){
		$this->layout->subtitle = '权限分类';
		Flash::set('如果您不清楚它的是干嘛用的，请不要随意修改，后果可能很严重！', 'warning');
		
		$this->view->cats = Category::service()->getTree('_system_action');
		$root_node = Category::service()->getByAlias('_system_action', 'id');
		$this->view->root = $root_node['id'];
		
		$this->layout->sublink = array(
			'uri'=>'#create-cat-dialog',
			'text'=>'添加权限分类',
			'html_options'=>array(
				'class'=>'create-cat-link',
				'data-title'=>'权限分类',
				'data-id'=>$root_node['id'],
			),
		);
		
		$this->view->render();
	}
}