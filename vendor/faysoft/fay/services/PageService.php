<?php
namespace fay\services;

use fay\core\Service;
use fay\core\Sql;
use fay\models\tables\PagesCategoriesTable;
use fay\models\tables\PagesTable;
use fay\models\tables\CategoriesTable;
use fay\helpers\StringHelper;

class PageService extends Service{
	
	/**
	 * @param string $class_name
	 * @return PageService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	public function getPageCats($id, $fields = '*'){
		$sql = new Sql();
		return $sql->from(array('pc'=>'pages_categories'), '')
			->joinLeft(array('c'=>'categories'), 'pc.cat_id = c.id', $fields)
			->where("pc.page_id = {$id}")
			->fetchAll();
	}
	
	public function getPageCatIds($id){
		return PagesCategoriesTable::model()->fetchCol('cat_id', "page_id = {$id}");
	}
	
	/**
	 * 根据分类别名获取页面
	 * @param string $alias
	 * @param int $limit
	 * @param mixed $fields
	 * @param bool $children 若为true，则会返回该分类及其所有子分类对应的页面
	 * @return array
	 */
	public function getByCatAlias($alias, $limit = 10, $fields = '!content', $children = false){
		$cat = CategoriesTable::model()->fetchRow(array(
			'alias = ?'=>$alias
		), 'id,left_value,right_value');
		
		$sql = new Sql();
		$sql->from(array('p'=>'pages'), PagesTable::model()->formatFields($fields))
			->joinLeft(array('pc'=>'pages_categories'), 'p.id = pc.page_id')
			->where(array(
				'deleted = 0',
				'status = '.PagesTable::STATUS_PUBLISHED,
			))
			->order('sort, id DESC')
			->distinct(true)
			->limit($limit);
		if($children){
			$all_cats = CategoriesTable::model()->fetchCol('id', array(
				'left_value >= '.$cat['left_value'],
				'right_value <= '.$cat['right_value'],
			));
			$sql->where(array(
				'pc.cat_id IN ('.implode(',', $all_cats).')',
			));
		}else{
			$sql->orWhere(array(
				"pc.cat_id = {$cat['id']}",
			));
		}
		return $sql->fetchAll();
	}
	
	/**
	 * 根据别名获取单页
	 * @param string $alias
	 * @return array|bool
	 */
	public function getByAlias($alias){
		return PagesTable::model()->fetchRow(array(
			'alias = ?'=>$alias,
		));
	}
	
	/**
	 * 根据ID获取单页
	 * @param int $id
	 * @return array|bool
	 */
	public function getById($id){
		return PagesTable::model()->find($id);
	}
	
	/**
	 * 获取单页
	 * @param int|string $page
	 *  - 若为数字，视为单页ID获取；
	 *  - 若为字符串，视为单页别名获取；
	 * @return array|bool
	 */
	public function get($page){
		if(StringHelper::isInt($page)){
			return $this->getById($page);
		}else{
			return $this->getByAlias($page);
		}
	}
}