<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Goods Counter model
 * 
 * @property int $goods_id 商品ID
 * @property int $views 浏览量
 * @property int $real_views 真实浏览量
 * @property int $sales 总销量
 * @property int $real_sales 真实总销量
 * @property int $reviews 评价数
 * @property int $real_reviews 真实评价数
 * @property int $favorites 收藏数
 * @property int $real_favorites 真实收藏数
 */
class GoodsCounterTable extends Table{
	protected $_name = 'goods_counter';
	protected $_primary = 'goods_id';
	
	/**
	 * @param string $class_name
	 * @return GoodsCounterTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('goods_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('views', 'real_views', 'sales', 'real_sales', 'reviews', 'real_reviews', 'favorites', 'real_favorites'), 'int', array('min'=>0, 'max'=>16777215)),
		);
	}

	public function labels(){
		return array(
			'goods_id'=>'商品ID',
			'views'=>'浏览量',
			'real_views'=>'真实浏览量',
			'sales'=>'总销量',
			'real_sales'=>'真实总销量',
			'reviews'=>'评价数',
			'real_reviews'=>'真实评价数',
			'favorites'=>'收藏数',
			'real_favorites'=>'真实收藏数',
		);
	}

	public function filters(){
		return array(
			'goods_id'=>'intval',
			'views'=>'intval',
			'real_views'=>'intval',
			'sales'=>'intval',
			'real_sales'=>'intval',
			'reviews'=>'intval',
			'real_reviews'=>'intval',
			'favorites'=>'intval',
			'real_favorites'=>'intval',
		);
	}
	
	public function getNotWritableFields($scene){
		/*
		 * 这些计数虽然可以伪造，但也不是直接手工输入的，必须有马甲帐号做对应。
		 * 所以这些字段不允许手工修改
		 */
		switch($scene){
			case 'insert':
				return array(
					'real_views',
					'sales', 'real_sales',
					'reviews', 'real_reviews',
					'favorites', 'real_favorites',
				);
			case 'update':
			default:
				return array(
					'goods_id', 'real_views',
					'sales', 'real_sales',
					'reviews', 'real_reviews',
					'favorites', 'real_favorites',
				);
		}
	}
}