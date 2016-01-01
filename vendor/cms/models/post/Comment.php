<?php
namespace cms\models\post;

use fay\core\Model;
use fay\models\tables\PostComments;

class Comment extends Model{
	/**
	 * @param string $class_name
	 * @return Comment
	 */
	public static function model($class_name=__CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 根据状态和类型，获取消息总数
	 * @param int $status
	 * @param int $type
	 */
	public function getCount($status = null){
		$conditions = array('deleted = 0');
		if($status !== null){
			$conditions['status = ?'] = $status;
		}
		$result = PostComments::model()->fetchRow(array(
			'deleted = 0',
			'status = ?'=>$status ? $status : false,
		), 'COUNT(*)');
		return $result['COUNT(*)'];
	}
	
	/**
	 * 根据给定的类型，获取回收站内消息总数
	 * @param int $type
	 */
	public function getDeletedCount(){
		$result = PostComments::model()->fetchRow(array('deleted = 1'), 'COUNT(*)');
		return $result['COUNT(*)'];
	}	
}