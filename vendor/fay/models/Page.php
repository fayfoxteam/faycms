<?php
namespace fay\models;

use fay\core\Model;
use fay\core\Sql;
use fay\models\tables\PageCategories;
use fay\models\tables\Pages;
use fay\models\tables\Categories;

class Page extends Model{
	
	/**
	 * @return Page
	 */
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	public function getPageCats($id, $fields = '*'){
		$sql = new Sql();
		return $sql->from('page_categories', 'pc', '')
			->joinLeft('categories', 'c', 'pc.cat_id = c.id', $fields)
			->where("pc.page_id = {$id}")
			->fetchAll();
	}
	
	public function getPageCatIds($id){
		return PageCategories::model()->fetchCol('cat_id', "page_id = {$id}");
	}
	
	public static function getPageStatus($status, $delete){
		if($delete == 1){
			return '回收站';
		}
		switch ($status) {
			case Pages::STATUS_PUBLISH:
				return '已发布';
				break;
			case Pages::STATUS_DRAFT:
				return '草稿';
				break;
		}
	}
	
	public function getPageCount($status = null){
		$conditions = array('deleted = 0');
		if($status !== null){
			$conditions['status = ?'] = $status;
		}
		$result = Pages::model()->fetchRow($conditions, 'COUNT(*)');
		return $result['COUNT(*)'];
	}
	
	public function getDeletedPageCount(){
		$result = Pages::model()->fetchRow('deleted = 1', 'COUNT(*)');
		return $result['COUNT(*)'];
	}
	
	/**
	 * 根据分类别名获取页面
	 * @param string $alias
	 * @param int $limit
	 * @param string $field
	 * @param bool $children 若为true，则会返回该分类及其所有子分类对应的页面
	 */
	public function getByCatAlias($alias, $limit = 10, $field = '!content', $children = false){
		$sql = new Sql();
		$cat = Categories::model()->fetchRow(array(
			'alias = ?'=>$alias
		), 'id,left_value,right_value');
		
		$sql = new Sql();
		$sql->from('pages', 'p', $field)
			->joinLeft('page_categories', 'pc', 'p.id = pc.page_id')
			->where(array(
				'deleted = 0',
				'status = '.Pages::STATUS_PUBLISH,
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
	 * 根据别名获取页面
	 * @param string $alias
	 */
	public function getByAlias($alias){
		$page = Pages::model()->fetchRow(array(
			'alias = ?'=>$alias,
		));
		return $page;
	}
	
	/**
	 * 根据ID获取页面
	 * @param unknown $id
	 */
	public function get($id){
		$page = Pages::model()->find($id);
		return $page;
	}
}