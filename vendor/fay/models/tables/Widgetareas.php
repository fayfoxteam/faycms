<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Widgetareas extends Table{
	protected $_name = 'widgetareas';
	
	/**
	 * @return Widgetareas
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('alias'), 'string', array('max'=>30)),
			array(array('description'), 'string', array('max'=>255)),
			array(array('deleted'), 'range', array('range'=>array('0', '1'))),
			
			array('alias', 'required'),
			array('alias', 'unique', array('table'=>'widgetareas', 'except'=>'id', 'ajax'=>array('admin/widgetarea/is-alias-not-exist'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'alias'=>'别名',
			'description'=>'描述',
			'deleted'=>'是否删除',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'alias'=>'trim',
			'description'=>'trim',
			'deleted'=>'intval',
		);
	}
}