<?php
namespace cms\models\post;

use fay\core\Model;
use fay\models\tables\PostCommentsTable;

class Comment extends Model{
	/**
	 * @param string $class_name
	 * @return Comment
	 */
	public static function model($class_name=__CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 根据状态，获取文章评论数
	 * @param int $status
	 * @return string
	 */
	public function getCount($status = null){
		$conditions = array('deleted = 0');
		if($status !== null){
			$conditions['status = ?'] = $status;
		}
		$result = PostCommentsTable::model()->fetchRow(array(
			'deleted = 0',
			'status = ?'=>$status ? $status : false,
		), 'COUNT(*)');
		return $result['COUNT(*)'];
	}
	
	/**
	 * 获取回收站内文章评论数
	 * @return string
	 */
	public function getDeletedCount(){
		$result = PostCommentsTable::model()->fetchRow(array('deleted = 1'), 'COUNT(*)');
		return $result['COUNT(*)'];
	}	
}