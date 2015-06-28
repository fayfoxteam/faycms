<?php
namespace fay\models\tables;

use fay\core\db\Table;

class CatPropValues extends Table{
	protected $_name = 'cat_prop_values';
	
	/**
	 * @return CatPropValues
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('cat_id', 'prop_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title'), 'string', array('max'=>255)),
			array(array('deleted'), 'range', array('range'=>array('0', '1'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'cat_id'=>'Cat Id',
			'prop_id'=>'Prop Id',
			'title'=>'Title',
			'deleted'=>'Deleted',
			'sort'=>'Sort',
		);
	}

	public function filters(){
		return array(
			'cat_id'=>'intval',
			'prop_id'=>'intval',
			'title'=>'trim',
			'deleted'=>'intval',
			'sort'=>'intval',
		);
	}
}