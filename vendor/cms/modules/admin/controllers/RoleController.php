<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\Roles;
use fay\models\tables\RolesActions;
use fay\models\tables\Actionlogs;
use fay\core\Response;
use fay\helpers\Html;
use fay\models\Category;
use fay\models\tables\RolesCats;
use fay\models\Flash;
use fay\models\Option;

class RoleController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'role';
	}
	
	public function index(){
		$this->layout->subtitle = '角色';
		
		$sql = new Sql();
		$sql->from('roles', 'r')
			->where(array(
				'deleted = 0',
			))
			->order('id DESC');
		$listview = new ListView($sql);
		$listview->page_size = 15;
		$this->view->listview = $listview;
		$this->view->render();
	}
	
	public function create(){
		$this->layout->subtitle = '添加角色';
		
		$this->form()->setModel(Roles::model());
		if($this->input->post()){
			if($this->form()->check()){
				$role_id = Roles::model()->insert($this->form()->getFilteredData());
				
				//操作权限
				$actions = $this->input->post('actions', 'intval', array());
				foreach($actions as $a){
					RolesActions::model()->insert(array(
						'role_id'=>$role_id,
						'action_id'=>$a,
					));
				}
				
				//分类权限
				$role_cats = $this->input->post('role_cats', 'intval', array());
				foreach($role_cats as $rc){
					RolesCats::model()->insert(array(
						'role_id'=>$role_id,
						'cat_id'=>$rc,
					));
				}
				
				$this->actionlog(Actionlogs::TYPE_ROLE, '添加了一个角色', $role_id);
				Response::output('success', '角色添加成功', array('admin/role/edit', array(
					'id'=>$role_id,
				)));
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		$sql = new Sql();
		$actions = $sql->from('actions', 'a')
			->joinLeft('categories', 'c', 'a.cat_id = c.id', 'title AS cat_title')
			->fetchAll();
		
		$actions_group = array();
		foreach($actions as $a){
			//若未开启文章审核，过滤掉文章审核权限
			$post_review = Option::get('system:post_review');
			if(($a['router'] == 'admin/post/review' || $a['router'] == 'admin/post/publish')
				&& !$post_review){
				continue;
			}
			
			$actions_group[$a['cat_title']][] = $a;
		}
		$this->view->actions = $actions_group;
		$this->view->cats = Category::model()->getTree('_system_post');
		
		$this->view->render();
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑角色';
		$this->layout->sublink = array(
			'uri'=>array('admin/role/create'),
			'text'=>'添加角色',
		);
		$role_id = $this->input->get('id', 'intval');
		
		$this->form()->setModel(Roles::model());
		if($this->input->post()){
			if($this->form()->check()){
				Roles::model()->update($this->form()->getFilteredData(), $role_id, true);
				
				//操作权限
				$actions = $this->input->post('actions', 'intval', array(0));
				RolesActions::model()->delete(array(
					'role_id = ?'=>$role_id,
					'action_id NOT IN (?)'=>$actions,
				));
				$old_actions = RolesActions::model()->fetchCol('action_id', array(
					'role_id = ?'=>$role_id,
				));
				
				foreach($actions as $a){
					if(!in_array($a, $old_actions)){
						RolesActions::model()->insert(array(
							'role_id'=>$role_id,
							'action_id'=>$a,
						));
					}
				}
				
				//分类权限
				$role_cats = $this->input->post('role_cats', 'intval', array(0));
				RolesCats::model()->delete(array(
					'role_id = ?'=>$role_id,
					'cat_id NOT IN (?)'=>$role_cats,
				));
				$old_role_cats = RolesCats::model()->fetchCol('cat_id', array(
					'role_id = ?'=>$role_id,
				));
				
				foreach($role_cats as $rc){
					if(!in_array($rc, $old_role_cats)){
						RolesCats::model()->insert(array(
							'role_id'=>$role_id,
							'cat_id'=>$rc,
						));
					}
				}

				$this->actionlog(Actionlogs::TYPE_ROLE, '编辑了一个角色', $role_id);
				Flash::set('一个角色被编辑', 'success');
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		$role = Roles::model()->find($role_id);
		$this->form()->setData($role);
		
		$this->form()->setData(array(
			'actions'=>RolesActions::model()->fetchCol('action_id', array('role_id = ?'=>$role_id)),
		));
		
		$sql = new Sql();
		$actions = $sql->from('actions', 'a')
			->joinLeft('categories', 'c', 'a.cat_id = c.id', 'title AS cat_title')
			->fetchAll();
		
		$actions_group = array();
		foreach($actions as $a){
			//若未开启文章审核，过滤掉文章审核权限
			$post_review = Option::get('system:post_review');
			if(($a['router'] == 'admin/post/review' || $a['router'] == 'admin/post/publish')
				&& !$post_review){
				continue;
			}
			
			$actions_group[$a['cat_title']][] = $a;
		}
		$this->view->actions = $actions_group;
		$this->form()->setData(array(
			'role_cats'=>RolesCats::model()->fetchCol('cat_id', array(
				'role_id = ?'=>$role_id,
			)),
		));
		$this->view->cats = Category::model()->getTree('_system_post');
		
		$this->view->render();
	}
	
	public function delete(){
		$role_id = $this->input->get('id', 'intval');
		Roles::model()->update(array(
			'deleted'=>1,
		), $role_id);
		$this->actionlog(Actionlogs::TYPE_ROLE, '删除了一个角色', $role_id);

		Response::output('success', array(
			'message'=>'一个角色被删除 - '.Html::link('撤销', array('admin/role/undelete', array(
				'id'=>$role_id,
			))),
			'id'=>$role_id,
		));
	}
	
	public function undelete(){
		$role_id = $this->input->get('id', 'intval');
		Roles::model()->update(array(
			'deleted'=>0,
		), $role_id);
		$this->actionlog(Actionlogs::TYPE_ROLE, '还原了一个角色', $role_id);

		Response::output('success', array(
			'message'=>'一个角色被还原',
			'id'=>$role_id,
		));
	}
	
	public function isTitleNotExist(){
		if(Roles::model()->fetchRow(array(
			'title = ?'=>$value = $this->input->post('value', 'trim'),
			'id != ?'=>$this->input->get('id', 'intval', 0),
		))){
			echo json_encode(array(
				'status'=>0,
				'message'=>'角色已存在',
			));
		}else{
			echo json_encode(array(
				'status'=>1,
			));
		}
	}
}