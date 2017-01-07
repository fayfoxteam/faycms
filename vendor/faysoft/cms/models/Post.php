<?php
namespace cms\models;

use fay\core\Model;
use fay\models\tables\PostsTable;

class Post extends Model{

	/**
	 * @param string $class_name
	 * @return Post
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 根据文章状态获取文章数
	 * @param int $status 文章状态
	 * @return string
	 */
	public function getCount($status = null){
		$conditions = array('deleted = 0');
		if($status !== null){
			$conditions['status = ?'] = $status;
		}
		$result = PostsTable::model()->fetchRow($conditions, 'COUNT(*)');
		return $result['COUNT(*)'];
	}
	
	/**
	 * 获取已删除的文章数
	 * @return string
	 */
	public function getDeletedCount(){
		$result = PostsTable::model()->fetchRow('deleted = 1', 'COUNT(*)');
		return $result['COUNT(*)'];
	}
}