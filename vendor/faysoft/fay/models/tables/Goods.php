<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Goods model
 *
 * @property int $id Id
 * @property int $cat_id 分类ID
 * @property string $title 标题
 * @property int $create_time 创建时间
 * @property int $last_modified_time 最后修改时间
 * @property int $publish_time 发布时间
 * @property int $user_id 用户ID
 * @property int $sub_stock 何时减库存
 * @property float $post_fee 运费
 * @property int $thumbnail 缩略图
 * @property int $num 库存
 * @property float $price 价格
 * @property int $status 状态
 * @property int $is_new 新品
 * @property int $is_hot 热销
 * @property int $deleted Deleted
 * @property int $sort 排序值
 */
class Goods extends Table{
	/**
	 * 状态 - 销售中
	 */
	const STATUS_ONSALE = 1;

	/**
	 * 状态 - 在库
	 */
	const STATUS_INSTOCK = 2;

	/**
	 * 拍下减库存
	 */
	const SUB_STOCK_CREATE = 1;

	/**
	 * 付款减库存
	 */
	const SUB_STOCK_PAY = 2;

	protected $_name = 'goods';
	
	/**
	 * @param string $class_name
	 * @return Goods
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id', 'user_id', 'thumbnail'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('cat_id', 'sort'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('num'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('sub_stock', 'status'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title'), 'string', array('max'=>255)),
			array(array('post_fee'), 'float', array('length'=>6, 'decimal'=>2)),
			array(array('price'), 'float', array('length'=>8, 'decimal'=>2)),
			array(array('is_new', 'is_hot', 'deleted'), 'range', array('range'=>array(0, 1))),
			array(array('publish_time'), 'datetime'),
		);
	}
	
	public function labels(){
		return array(
			'id'=>'Id',
			'cat_id'=>'分类ID',
			'title'=>'标题',
			'create_time'=>'创建时间',
			'last_modified_time'=>'最后修改时间',
			'publish_time'=>'发布时间',
			'user_id'=>'用户ID',
			'sub_stock'=>'何时减库存',
			'post_fee'=>'运费',
			'thumbnail'=>'缩略图',
			'num'=>'库存',
			'price'=>'价格',
			'status'=>'状态',
			'is_new'=>'新品',
			'is_hot'=>'热销',
			'deleted'=>'Deleted',
			'sort'=>'排序值',
		);
	}
	
	public function filters(){
		return array(
			'id'=>'intval',
			'cat_id'=>'intval',
			'title'=>'trim',
			'publish_time'=>'trim',
			'user_id'=>'intval',
			'sub_stock'=>'intval',
			'post_fee'=>'floatval',
			'thumbnail'=>'intval',
			'num'=>'intval',
			'price'=>'floatval',
			'status'=>'intval',
			'is_new'=>'intval',
			'is_hot'=>'intval',
			'deleted'=>'intval',
			'sort'=>'intval',
		);
	}
	
	public function getNotWritableFields($scene){
		switch($scene){
			case 'update':
				return array(
					'id', 'create_time', 'last_modified_time'
				);
			case 'insert':
			default:
				return array(
					'id', 'create_time', 'last_modified_time'
				);
		}
	}
}