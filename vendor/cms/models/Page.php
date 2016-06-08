<?php
namespace cms\models;

use fay\core\Model;
use fay\models\tables\Pages;

class Page extends Model{

	/**
	 * @param string $class_name
	 * @return Page
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 根据页面状态获取页面数
	 * @param int $status 页面状态
	 * @return string
	 */
	public function getCount($status = null){
		$conditions = array('deleted = 0');
		if($status !== null){
			$conditions['status = ?'] = $status;
		}
		$result = Pages::model()->fetchRow($conditions, 'COUNT(*)');
		return $result['COUNT(*)'];
	}
	
	/**
	 * 获取已删除的页面数
	 * @return string
	 */
	public function getDeletedCount(){
		$result = Pages::model()->fetchRow('deleted = 1', 'COUNT(*)');
		return $result['COUNT(*)'];
	}
}