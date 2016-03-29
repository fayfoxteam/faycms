<?php
namespace cms\models;

use fay\core\Model;
use fay\models\tables\Feeds;

class Feed extends Model{

	/**
	 * @return Feed
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 根据动态状态获取动态数
	 * @param int $status 动态状态
	 */
	public function getCount($status = null){
		$conditions = array('deleted = 0');
		if($status !== null){
			$conditions['status = ?'] = $status;
		}
		$result = Feeds::model()->fetchRow($conditions, 'COUNT(*)');
		return $result['COUNT(*)'];
	}
	
	/**
	 * 获取已删除的动态数
	 */
	public function getDeletedCount(){
		$result = Feeds::model()->fetchRow('deleted = 1', 'COUNT(*)');
		return $result['COUNT(*)'];
	}
}