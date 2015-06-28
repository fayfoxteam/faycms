<?php
namespace fay\models;

use fay\core\Model;
use fay\core\Sql;
use fay\models\tables\PagesCategories;
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
		return $sql->from('pages_categories', 'pc', '')
			->joinLeft('categories', 'c', 'pc.cat_id = c.id', $fields)
			->where("pc.page_id = {$id}")
			->fetchAll();
	}
	
	public function getPageCatIds($id){
		return PagesCategories::model()->fetchCol('cat_id', "page_id = {$id}");
	}
	
	public static function getPageStatus($status, $delete){
		if($delete == 1){
			return '回收站';
		}
		switch ($status) {
			case Pages::STATUS_PUBLISHED:
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
			->joinLeft('pages_categories', 'pc', 'p.id = pc.page_id')
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
		if(is_numeric($page)){
			return $this->getById($page);
		}else{
			return $this->getByAlias($page);
		}
	}
}