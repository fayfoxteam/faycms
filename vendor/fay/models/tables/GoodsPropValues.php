<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Goods Prop Values model
 * 
 * @property int $goods_id
 * @property int $prop_id
 * @property int $prop_value_id
 * @property string $prop_value_alias
 */
class GoodsPropValues extends Table{
	protected $_name = 'goods_prop_values';
	protected $_primary = array('goods_id', 'prop_id', 'prop_value_id');
	
	/**
	 * @return GoodsPropValues
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('goods_id', 'prop_value_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('prop_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('prop_value_alias'), 'string', array('max'=>255)),
		);
	}

	public function labels(){
		return array(
			'goods_id'=>'商品Id',
			'prop_id'=>'属性Id',
			'prop_value_id'=>'属性值Id',
			'prop_value_alias'=>'属性别名',
		);
	}

	public function filters(){
		return array(
			'goods_id'=>'intval',
			'prop_id'=>'intval',
			'prop_value_id'=>'intval',
			'prop_value_alias'=>'trim',
		);
	}
}