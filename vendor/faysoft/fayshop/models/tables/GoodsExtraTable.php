<?php
namespace fayshop\models\tables;

use fay\core\db\Table;

/**
 * Goods Extra model
 * 
 * @property int $goods_id 商品ID
 * @property string $seo_title SEO Title
 * @property string $seo_keywords SEO Keywords
 * @property string $seo_description SEO Description
 * @property int $ip_int IP
 * @property float $weight 单位:kg
 * @property float $size 单位:立方米
 * @property string $sn 货号
 * @property string $rich_text 富文本描述
 */
class GoodsExtraTable extends Table{
	protected $_name = 'goods_extra';
	protected $_primary = 'goods_id';
	
	/**
	 * @param string $class_name
	 * @return GoodsExtraTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('goods_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('seo_title', 'seo_keywords'), 'string', array('max'=>255)),
			array(array('seo_description'), 'string', array('max'=>500)),
			array(array('sn'), 'string', array('max'=>50)),
			array(array('weight', 'size'), 'float', array('length'=>8, 'decimal'=>2)),
		);
	}

	public function labels(){
		return array(
			'goods_id'=>'商品ID',
			'seo_title'=>'SEO Title',
			'seo_keywords'=>'SEO Keywords',
			'seo_description'=>'SEO Description',
			'ip_int'=>'IP',
			'weight'=>'单位:kg',
			'size'=>'单位:立方米',
			'sn'=>'货号',
			'rich_text'=>'富文本描述',
		);
	}

	public function filters(){
		return array(
			'goods_id'=>'intval',
			'seo_title'=>'trim',
			'seo_keywords'=>'trim',
			'seo_description'=>'trim',
			'weight'=>'floatval',
			'size'=>'floatval',
			'sn'=>'trim',
			'rich_text'=>'',
		);
	}
	
	public function getNotWritableFields($scene){
		switch($scene){
			case 'update':
				return array(
					'goods_id', 'ip_int',
				);
			case 'insert':
			default:
				return array();
		}
	}
}