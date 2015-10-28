<?php
namespace fay\models\tables;

use fay\core\db\Table;

class ItemPropValues extends Table{
	protected $_name = 'item_prop_values';
	
	/**
	 * @return ItemPropValues
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('cat_id', 'prop_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title', 'title_alias'), 'string', array('max'=>255)),
			array(array('is_terminal', 'deleted'), 'range', array('range'=>array(0, 1))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'cat_id'=>'Cat Id',
			'prop_id'=>'Prop Id',
			'title'=>'Title',
			'title_alias'=>'Title Alias',
			'is_terminal'=>'Is Terminal',
			'deleted'=>'Deleted',
			'sort'=>'Sort',
		);
	}

	public function filters(){
		return array(
			'cat_id'=>'intval',
			'prop_id'=>'intval',
			'title'=>'trim',
			'title_alias'=>'trim',
			'is_terminal'=>'intval',
			'deleted'=>'intval',
			'sort'=>'intval',
		);
	}
}