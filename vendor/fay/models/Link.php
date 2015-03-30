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
	public function getByCatId($cat_id, $limit = 0, $visiable = 1){
		return Links::model()->fetchAll(array(
			'cat_id = '.$cat_id,
			'visiable = ?'=>$visiable,
		), '*', false, $limit ? $limit : false);
	}
	
	public function getByCatAlias($cat_alias, $limit = 0, $visiable = 1){
		$cat = Category::model()->getByAlias($cat_alias, 'id');
		return $this->getByCatId($cat['id'], $limit, $visiable);
	}
	
	public function getByCat($cat, $limit = 0, $visiable = 1){
		return $this->getByCatId($cat['id'], $limit, $visiable);
	}
	
	/**
	 * 获取带有logo的链接
	 * @param null|int $cat_id 若为null，则不限制分类
	 * @param int $limit
	 * @param int|false $visiable 若为false，则不限制
	 */
	public function getLinksHasLogo($cat_id = null, $limit = 0, $visiable = 1){
		$conditions = array(
			'logo != 0',
			'visiable = ?'=>$visiable,
		);
		if($cat_id){
			$conditions[] = 'cat_id = '.$cat_id;
		}
		return Links::model()->fetchAll($conditions, '*', 'sort', $limit ? $limit : false);
	}
}