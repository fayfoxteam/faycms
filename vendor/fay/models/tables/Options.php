<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Options extends Table{
	protected $_name = 'options';
	
	/**
	 * @return Options
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id', 'create_time', 'last_modified_time'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('option_name'), 'string', array('max'=>255)),
			array(array('description'), 'string', array('max'=>500)),
			array(array('is_system'), 'range', array('range'=>array('0', '1'))),

			array('option_name', 'required'),
			array('option_name', 'unique', array('table'=>'options', 'except'=>'id', 'ajax'=>array('admin/option/is-option-not-exist'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'option_name'=>'参数名',
			'option_value'=>'参数值',
			'description'=>'Description',
			'create_time'=>'Create Time',
			'last_modified_time'=>'Last Modified Time',
			'is_system'=>'Is System',
		);
	}

	public function filters(){
		return array(
			'option_name'=>'trim',
			'option_value'=>'',
			'description'=>'trim',
			'create_time'=>'',
			'last_modified_time'=>'',
			'is_system'=>'intval',
		);
	}
}