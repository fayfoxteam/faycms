<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Widgets extends Table{
	protected $_name = 'widgets';
	
	/**
	 * @return Widgets
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('enabled'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('alias', 'widget_name', 'description'), 'string', array('max'=>255)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'alias'=>'Alias',
			'options'=>'Options',
			'widget_name'=>'Widget Name',
			'description'=>'Description',
			'enabled'=>'Enabled',
		);
	}

	public function filters(){
		return array(
			'alias'=>'trim',
			'options'=>'',
			'widget_name'=>'trim',
			'description'=>'trim',
			'enabled'=>'intval',
		);
	}
}