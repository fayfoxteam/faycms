<?php
namespace cms\models;

use fay\core\Model;
use fay\models\tables\Messages;
use fay\core\Sql;

class Message extends Model{
	/**
	 * @param string $className
	 * @return Message
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 根据状态和类型，获取消息总数
	 * @param int $status
	 * @param int $type
	 */
	public function getCount($status = null, $type = array()){
		$conditions = array('deleted = 0');
		if($type){
			if(!is_array($type)){
				$type = explode(',', $type);
			}
			$conditions[] = 'type IN ('.implode(',', $type).')';
		}
		if($status !== null){
			$conditions['status = ?'] = $status;
		}
		$result = Messages::model()->fetchRow($conditions, 'COUNT(*)');
		return $result['COUNT(*)'];
	}
	
	/**
	 * 根据给定的类型，获取回收站内消息总数
	 * @param int $type
	 */
	public function getDeletedCount($type = array()){
		$conditions = array('deleted = 1');
		if($type){
			if(!is_array($type)){
				$type = explode(',', $type);
			}
			$conditions[] = 'type IN ('.implode(',', $type).')';
		}
		$result = Messages::model()->fetchRow($conditions, 'COUNT(*)');
		return $result['COUNT(*)'];
	}
	
	/**
	 * 获取回复数（不包含回收站里的）
	 * @param int $id
	 */
	public function getReplyCount($id, $status = false){
		$message = Messages::model()->fetchRow(array(
			'root = ?'=>$id,
			'status = ?'=>$status,
			'deleted = 0',
		), 'COUNT(*) AS count');
		return $message['count'];
	}
	
}