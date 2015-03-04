<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Links;

class Link extends Model{
	/**
	 * @return Link
	 */
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 友情链接的分类体系比较简单，一般不会做多级分类，故不做父子关系搜索
	 */
	public function getByCatId($cat_id, $limit = 0){
		return Links::model()->fetchAll('cat_id = '.$cat_id, '*', false, $limit ? $limit : false);
	}
	
	public function getByCatAlias($cat_alias, $limit = 0){
		$cat = Category::model()->getByAlias($cat_alias, 'id');
		return $this->getByCatId($cat['id'], $limit);
	}
	
	public function getByCat($cat, $limit = 0){
		return $this->getByCatId($cat['id'], $limit);
	}
	
	/**
	 * 获取带有logo的链接
	 * @param null|int $cat_id 若为null，则不限制分类
	 * @param int $limit
	 */
	public function getLinksHasLogo($cat_id = null, $limit){
		return Links::model()->fetchAll($cat_id !== null ? 'cat_id = '.$cat_id : array(), '*', false, $limit ? $limit : false);
	}
}