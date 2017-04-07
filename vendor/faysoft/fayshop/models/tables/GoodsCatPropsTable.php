<?php
namespace fayshop\models\tables;

use fay\core\db\Table;

/**
 * Goods Cat Props model
 * 
 * @property int $id
 * @property string $alias
 * @property int $type
 * @property int $cat_id
 * @property int $required
 * @property string $title
 * @property int $is_sale_prop
 * @property int $is_input_prop
 * @property int $delete_time 删除时间
 * @property int $sort
 */
class GoodsCatPropsTable extends Table{
	/**
	 * 属性类型 - 多选
	 */
	const TYPE_CHECK = 1;

	/**
	 * 属性类型 - 单选
	 */
	const TYPE_OPTIONAL = 2;

	/**
	 * 属性类型 - 手工录入
	 */
	const TYPE_INPUT = 3;

	protected $_name = 'goods_cat_props';
	
	/**
	 * @param string $class_name
	 * @return GoodsCatPropsTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('required'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('type', 'sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title'), 'string', array('max'=>255)),
			array(array('is_sale_prop', 'is_input_prop', 'delete_time'), 'range', array('range'=>array(0, 1))),
			
			array('alias', 'unique', array('table'=>'props', 'field'=>'alias', 'except'=>'id', 'ajax'=>array('admin/goods-cat-prop/is-alias-not-exist'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'alias'=>'别名',
			'type'=>'编辑框类型',
			'cat_id'=>'分类ID',
			'required'=>'必选标记',
			'title'=>'标题',
			'is_sale_prop'=>'是否销售属性',
			'is_input_prop'=>'是否可自定义属性',
			'delete_time'=>'删除时间',
			'sort'=>'排序值',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'alias'=>'trim',
			'type'=>'intval',
			'cat_id'=>'intval',
			'required'=>'intval',
			'title'=>'trim',
			'is_sale_prop'=>'intval',
			'is_input_prop'=>'intval',
			'delete_time'=>'intval',
			'sort'=>'intval',
		);
	}
}