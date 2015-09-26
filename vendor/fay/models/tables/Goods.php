<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Goods model
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $create_time
 * @property int $last_modified_time
 * @property int $publish_time
 * @property int $sub_stock
 * @property float $weight
 * @property float $size
 * @property string $sn
 * @property int $cat_id
 * @property int $thumbnail
 * @property int $num
 * @property float $price
 * @property int $status
 * @property int $is_new
 * @property int $is_hot
 * @property int $deleted
 * @property int $sort
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
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
	 * @return Goods
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('create_time', 'last_modified_time', 'thumbnail'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('id', 'cat_id', 'sort'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('num'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('sub_stock', 'status'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title', 'seo_title', 'seo_keywords', 'seo_description'), 'string', array('max'=>255)),
			array(array('sn'), 'string', array('max'=>50)),
			array(array('weight', 'size', 'price'), 'float', array('length'=>8, 'decimal'=>2)),
			array(array('is_new', 'is_hot', 'deleted'), 'range', array('range'=>array('0', '1'))),
			array(array('publish_time'), 'datetime'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'title'=>'标题',
			'description'=>'描述',
			'create_time'=>'创建时间',
			'last_modified_time'=>'最后修改时间',
			'publish_time'=>'发布时间',
			'sub_stock'=>'何时减库存',
			'weight'=>'单位:kg',
			'size'=>'单位:立方米',
			'sn'=>'Sn',
			'cat_id'=>'Cat Id',
			'thumbnail'=>'Thumbnail',
			'num'=>'库存',
			'price'=>'价格',
			'status'=>'Status',
			'is_new'=>'新品',
			'is_hot'=>'热销',
			'deleted'=>'Deleted',
			'sort'=>'Sort',
			'seo_title'=>'Seo Title',
			'seo_keywords'=>'Seo Keywords',
			'seo_description'=>'Seo Description',
		);
	}

	public function filters(){
		return array(
			'title'=>'trim',
			'description'=>'',
			'create_time'=>'',
			'last_modified_time'=>'',
			'publish_time'=>'trim',
			'sub_stock'=>'intval',
			'weight'=>'floatval',
			'size'=>'floatval',
			'sn'=>'trim',
			'cat_id'=>'intval',
			'thumbnail'=>'intval',
			'num'=>'intval',
			'price'=>'floatval',
			'status'=>'intval',
			'is_new'=>'intval',
			'is_hot'=>'intval',
			'deleted'=>'intval',
			'sort'=>'intval',
			'seo_title'=>'trim',
			'seo_keywords'=>'trim',
			'seo_description'=>'trim',
		);
	
	}
}