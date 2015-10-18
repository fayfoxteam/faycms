<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Templates extends Table{
	/**
	 * 邮件
	 */
	const TYPE_EMAIL = 1;
	
	/**
	 * 短信
	 */
	const TYPE_SMS = 2;
	
	/**
	 * 站内信
	 */
	const TYPE_NOTIFICATION = 3;

	protected $_name = 'templates';
	
	/**
	 * @return Templates
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id', 'create_time'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('enable'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('type'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title'), 'string', array('max'=>500)),
			array(array('alias'), 'string', array('max'=>50, 'format'=>'alias')),
			array(array('deleted'), 'range', array('range'=>array('0', '1'))),
			
			array(array('alias'), 'unique', array('table'=>'templates', 'field'=>'alias', 'except'=>'id', 'ajax'=>array('admin/template/is-alias-not-exist'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'title'=>'标题',
			'content'=>'内容',
			'create_time'=>'创建时间',
			'enable'=>'启用',
			'deleted'=>'删除',
			'description'=>'对模版的描述',
			'type'=>'类型',
			'alias'=>'别名',
		);
	}

	public function filters(){
		return array(
			'title'=>'trim',
			'content'=>'',
			'create_time'=>'',
			'enable'=>'intval',
			'deleted'=>'intval',
			'description'=>'',
			'type'=>'intval',
			'alias'=>'trim',
		);
	}
}