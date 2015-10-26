<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Actions extends Table{
	protected $_name = 'actions';
	
	/**
	 * @return Actions
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('id', 'parent'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('title', 'router'), 'string', array('max'=>255)),
			array(array('is_public'), 'range', array('range'=>array(0, 1))),
			
			array(array('title', 'router'), 'required'),
			array('router', 'unique', array('table'=>'actions', 'except'=>'id', 'ajax'=>array('admin/action/is-router-not-exist'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'title'=>'操作',
			'router'=>'路由',
			'cat_id'=>'分类',
			'is_public'=>'是否为公共路由',
			'parent'=>'Parent',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'title'=>'trim',
			'router'=>'trim',
			'cat_id'=>'intval',
			'is_public'=>'intval',
			'parent'=>'intval',
		);
	}
}