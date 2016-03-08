<?php
namespace fay\models;

use fay\core\Model;
use fay\core\Sql;
use fay\models\tables\PagesCategories;
use fay\models\tables\Pages;
use fay\models\tables\Categories;
use fay\helpers\StringHelper;

class Page extends Model{
	
	/**
	 * @return Page
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function getPageCats($id, $fields = '*'){
		$sql = new Sql();
		return $sql->from(array('pc'=>'pages_categories'), '')
			->joinLeft(array('c'=>'categories'), 'pc.cat_id = c.id', $fields)
			->where("pc.page_id = {$id}")
			->fetchAll();
	}
	
	public function getPageCatIds($id){
		return PagesCategories::model()->fetchCol('cat_id', "page_id = {$id}");
	}
	
	/**
	 * 根据分类别名获取页面
	 * @param string $alias
	 * @param int $limit
	 * @param string $field
	 * @param bool $children 若为true，则会返回该分类及其所有子分类对应的页面
	 */
	public function getByCatAlias($alias, $limit = 10, $fields = '!content', $children = false){
		$sql = new Sql();
		$cat = Categories::model()->fetchRow(array(
			'alias = ?'=>$alias
		), 'id,left_value,right_value');
		
		$sql = new Sql();
		$sql->from(array('p'=>'pages'), Pages::model()->formatFields($fields))
			->joinLeft(array('pc'=>'pages_categories'), 'p.id = pc.page_id')
			->where(array(
				'deleted = 0',
				'status = '.Pages::STATUS_PUBLISHED,
			))
			->order('sort, id DESC')
			->distinct(true)
			->limit($limit);
		if($children){
			$all_cats = Categories::model()->fetchCol('id', array(
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
	 */
	public function getByAlias($alias){
		return Pages::model()->fetchRow(array(
			'alias = ?'=>$alias,
		));
	}
	
	/**
	 * 根据ID获取单页
	 * @param int $id
	 */
	public function getById($id){
		return Pages::model()->find($id);
	}
	
	/**
	 * 获取单页
	 * @param int|string $page
	 *  - 若为数字，视为单页ID获取；
	 *  - 若为字符串，视为单页别名获取；
	 */
	public function get($page){
		if(StringHelper::isInt($page)){
			return $this->getById($page);
		}else{
			return $this->getByAlias($page);
		}
	}
}