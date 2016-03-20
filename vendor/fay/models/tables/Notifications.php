<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Notifications extends Table{
	protected $_name = 'notifications';
	
	/**
	 * @return Notifications
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id', 'sender'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('title'), 'string', array('max'=>255)),
			array(array('active_key'), 'string', array('max'=>32)),
			array(array('publish_time', 'validity_time'), 'datetime'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'title'=>'标题',
			'content'=>'内容',
			'sender'=>'发件人',
			'cat_id'=>'分类ID',
			'active_key'=>'随机码',
			'create_time'=>'创建时间',
			'publish_time'=>'发布时间',
			'validity_time'=>'有效期',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'title'=>'trim',
			'content'=>'',
			'sender'=>'intval',
			'cat_id'=>'intval',
			'active_key'=>'trim',
			'publish_time'=>'trim',
			'validity_time'=>'trim',
		);
	}
}