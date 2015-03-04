<?php
namespace fay\models\tables;

use fay\core\db\Table;

class PropValues extends Table{
	protected $_name = 'prop_values';
	
	/**
	 * @return PropValues
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('refer', 'prop_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('default'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title'), 'string', array('max'=>255)),
			array(array('deleted'), 'range', array('range'=>array('0', '1'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'refer'=>'Refer',
			'prop_id'=>'Prop Id',
			'title'=>'Title',
			'default'=>'Default',
			'deleted'=>'Deleted',
			'sort'=>'Sort',
		);
	}

	public function filters(){
		return array(
			'refer'=>'intval',
			'prop_id'=>'intval',
			'title'=>'trim',
			'default'=>'intval',
			'deleted'=>'intval',
			'sort'=>'intval',
		);
	}
}