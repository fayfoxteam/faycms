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
	 * 获取友链
	 * @param int|string|array $cat
	 *  - 若为数字，视为分类ID
	 *  - 若为字符串，视为分类别名
	 *  - 若为数组，则至少包含id字段
	 *  - 若等价于false，则不限制分类
	 * @param int $limit 获取条数，若为0，则全部取出
	 * @param 0|1|false $visiable
	 *  - 1：仅返回允许显示的友链
	 *  - 0：仅返回不允许显示的友链
	 *  - false：不做限制
	 */
	public function get($cat = 0, $limit = 0, $visiable = 1){
		if($cat == false){
			return Links::model()->fetchAll(array(
				'visiable = ?'=>$visiable,
			), '*', false, $limit ? $limit : false);
		}else{
			if(is_numeric($cat)){
				return $this->getByCatId($cat, $limit, $visiable);
			}else if(is_array($cat)){
				return $this->getByCat($cat, $limit, $visiable);
			}else{
				return $this->getByCatAlias($cat, $limit, $visiable);
			}
		}
	}
	
	/**
	 * 根据分类ID获取友情链接
	 * @param int $cat_id
	 * @param int $limit 获取条数，若为0，则全部取出
	 * @param 0|1|false $visiable
	 *  - 1：仅返回允许显示的友链
	 *  - 0：仅返回不允许显示的友链
	 *  - false：不做限制
	 */
	public function getByCatId($cat_id, $limit = 0, $visiable = 1){
		return Links::model()->fetchAll(array(
			'cat_id = '.$cat_id,
			'visiable = ?'=>$visiable,
		), '*', false, $limit ? $limit : false);
	}

	/**
	 * 根据分类别名获取友情链接
	 * @param string $cat_alias
	 * @param int $limit 获取条数，若为0，则全部取出
	 * @param 0|1|false $visiable
	 *  - 1：仅返回允许显示的友链
	 *  - 0：仅返回不允许显示的友链
	 *  - false：不做限制
	 */
	public function getByCatAlias($cat_alias, $limit = 0, $visiable = 1){
		$cat = Category::model()->getByAlias($cat_alias, 'id');
		return $this->getByCatId($cat['id'], $limit, $visiable);
	}

	/**
	 * 根据分类数组获取友情链接
	 * @param array $cat
	 * @param int $limit 获取条数，若为0，则全部取出
	 * @param 0|1|false $visiable
	 *  - 1：仅返回允许显示的友链
	 *  - 0：仅返回不允许显示的友链
	 *  - false：不做限制
	 */
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