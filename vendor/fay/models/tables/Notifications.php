<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Notifications extends Table{
	protected $_name = 'notifications';
	
	/**
	 * @return Notifications
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id', 'create_time'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('from', 'cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('title'), 'string', array('max'=>255)),
			array(array('active_key'), 'string', array('max'=>32)),
			array(array('publish_time', 'validity_time'), 'datetime'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'title'=>'Title',
			'content'=>'Content',
			'from'=>'From',
			'cat_id'=>'Cat Id',
			'active_key'=>'Active Key',
			'create_time'=>'Create Time',
			'publish_time'=>'Publish Time',
			'validity_time'=>'Validity Time',
		);
	}

	public function filters(){
		return array(
			'title'=>'trim',
			'content'=>'',
			'from'=>'intval',
			'cat_id'=>'intval',
			'active_key'=>'trim',
			'create_time'=>'',
			'publish_time'=>'trim',
			'validity_time'=>'trim',
		);
	}
}