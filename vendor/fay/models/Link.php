<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Links;
use fay\helpers\StringHelper;

class Link extends Model{
	/**
	 * @param string $class_name
	 * @return Link
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取友链
	 * @param mixed $cat
	 *  - 若为数字，视为分类ID
	 *  - 若为字符串，视为分类别名
	 *  - 若为数组，则至少包含id字段
	 *  - 若等价于false，则不限制分类
	 * @param int $limit 获取条数，若为0，则全部取出
	 * @param mixed $visible 可见性
	 *  - true或等价于true：仅返回允许显示的友链
	 *  - false或等价于false（但不包括null）：仅返回不允许显示的友链
	 *  - null：不做限制
	 * @return array
	 */
	public function get($cat = false, $limit = 0, $visible = true){
		if($cat){
			if(StringHelper::isInt($cat)){
				return $this->getByCatId($cat, $limit, $visible);
			}else if(is_array($cat)){
				return $this->getByCat($cat, $limit, $visible);
			}else{
				return $this->getByCatAlias($cat, $limit, $visible);
			}
		}else{
			return Links::model()->fetchAll(array(
				'visible = ?'=>$visible === null ? false : ($visible == true ? 1 : 0),
			), '*', false, $limit ? $limit : false);
		}
	}
	
	/**
	 * 根据分类ID获取友情链接
	 * @param int $cat_id
	 * @param int $limit 获取条数，若为0，则全部取出
	 * @param mixed $visible 可见性
	 *  - true或等价于true：仅返回允许显示的友链
	 *  - false或等价于false（但不包括null）：仅返回不允许显示的友链
	 *  - null：不做限制
	 * @return array
	 */
	public function getByCatId($cat_id, $limit = 0, $visible = true){
		return Links::model()->fetchAll(array(
			'cat_id = ' . $cat_id,
			'visible = ?'=>$visible === null ? false : ($visible == true ? 1 : 0),
		), '*', false, $limit ? $limit : false);
	}
	
	/**
	 * 根据分类别名获取友情链接
	 * @param string $cat_alias
	 * @param int $limit 获取条数，若为0，则全部取出
	 * @param mixed $visible 可见性
	 *  - true或等价于true：仅返回允许显示的友链
	 *  - false或等价于false（但不包括null）：仅返回不允许显示的友链
	 *  - null：不做限制
	 * @return array
	 * @return array
	 */
	public function getByCatAlias($cat_alias, $limit = 0, $visible = true){
		$cat = Category::model()->getByAlias($cat_alias, 'id');
		return $this->getByCatId($cat['id'], $limit, $visible);
	}

	/**
	 * 根据分类数组获取友情链接
	 * @param array $cat
	 * @param int $limit 获取条数，若为0，则全部取出
	 * @param mixed $visible 可见性
	 *  - true或等价于true：仅返回允许显示的友链
	 *  - false或等价于false（但不包括null）：仅返回不允许显示的友链
	 *  - null：不做限制
	 * @return array
	 */
	public function getByCat($cat, $limit = 0, $visible = true){
		return $this->getByCatId($cat['id'], $limit, $visible);
	}
	
	/**
	 * 获取带有logo的链接
	 * @param null|int $cat_id 若为null，则不限制分类
	 * @param int $limit
	 * @param mixed $visible 可见性
	 *  - true或等价于true：仅返回允许显示的友链
	 *  - false或等价于false（但不包括null）：仅返回不允许显示的友链
	 *  - null：不做限制
	 * @return array
	 */
	public function getLinksHasLogo($cat_id = null, $limit = 0, $visible = true){
		$conditions = array(
			'logo != 0',
			'visible = ?'=>$visible === null ? false : ($visible == true ? 1 : 0),
		);
		if($cat_id){
			$conditions[] = 'cat_id = '.$cat_id;
		}
		return Links::model()->fetchAll($conditions, '*', 'sort', $limit ? $limit : false);
	}
}