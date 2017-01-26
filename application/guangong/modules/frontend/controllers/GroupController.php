<?php
namespace guangong\modules\frontend\controllers;

use guangong\library\FrontController;
use guangong\models\forms\CreateGroupForm;
use guangong\models\tables\GuangongUserGroupsTable;

/**
 * 义结金兰
 */
class GroupController extends FrontController{
	public function index(){
		$this->form()->setModel(CreateGroupForm::model());
		
		$this->view->render();
	}
	
	public function step2(){
		//表单验证
		$this->form()->setRules(array(
			array(array('group_id'), 'required'),
			array(array('group_idd'), 'int', array('min'=>1)),
			array(array('group_id'), 'exist', array(
				'table'=>'guangong_user_groups',
				'field'=>'id',
			)),
		))->setFilters(array(
			'id'=>'intval',
		))->setLabels(array(
			'id'=>'结义ID',
		))->check();
		
		$group = GuangongUserGroupsTable::model()->find($this->form()->getData('group_id'));
		$this->view->assign(array(
			'group'=>$group,
		))->render();
	}
	
	public function step3(){
		//表单验证
		$this->form()->setRules(array(
			array(array('group_id'), 'required'),
			array(array('group_idd'), 'int', array('min'=>1)),
			array(array('group_id'), 'exist', array(
				'table'=>'guangong_user_groups',
				'field'=>'id',
			)),
		))->setFilters(array(
			'id'=>'intval',
		))->setLabels(array(
			'id'=>'结义ID',
		))->check();
		
		$group = GuangongUserGroupsTable::model()->find($this->form()->getData('group_id'));
		$this->view->assign(array(
			'group'=>$group,
		))->render();
	}
}