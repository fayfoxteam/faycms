<?php
namespace cms\models;

use fay\core\Model;
use fay\models\tables\Posts;

class Post extends Model{

	/**
	 * @return Post
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 根据文章状态获取文章数
	 * @param int $status 文章状态
	 */
	public function getCount($status = null){
		$conditions = array('deleted = 0');
		if($status !== null){
			$conditions['status = ?'] = $status;
		}
		$result = Posts::model()->fetchRow($conditions, 'COUNT(*)');
		return $result['COUNT(*)'];
	}
	
	/**
	 * 获取已删除的文章数
	 */
	public function getDeletedCount(){
		$result = Posts::model()->fetchRow('deleted = 1', 'COUNT(*)');
		return $result['COUNT(*)'];
	}
}