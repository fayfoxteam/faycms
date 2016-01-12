<?php
namespace fay\models;

use fay\core\Model;
use fay\helpers\SqlHelper;
use fay\models\tables\Messages;

class Message extends Model{
	/**
	 * @param string $class_name
	 * @return Message
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取一条信息
	 * @param string $model 表模型
	 * @param int $message_id 消息ID
	 * @param int $fields 返回字段
	 *  - message.*系列可指定$model对应表返回字段，若有一项为'message.*'，则返回所有字段
	 *  - user.*系列可指定作者信息，格式参照\fay\models\User::get()
	 *  - parent_message.*系列可指定父消息post_messages表返回字段，若有一项为'message.*'，则返回所有字段
	 *  - parent_message_user.*系列可指定父消息作者信息，格式参照\fay\models\User::get()
	 */
	public function get($model, $message_id, $fields = 'message.*,user.nickname,user.avatar,parent_message.content,parent_message.user_id,parent_message_user.nickname,parent_message_user.avatar'){
		//解析$fields
		$fields = SqlHelper::processFields($fields, 'message');
		if(empty($fields['message']) || in_array('*', $fields['message'])){
			//若未指定返回字段，初始化
			$fields['message'] = \F::model($model)->getFields(array('status', 'deleted', 'sockpuppet'));
		}
		
		$message_fields = $fields['message'];
		if(!empty($fields['user']) && !in_array('user_id', $message_fields)){
			//如果要获取作者信息，则必须搜出user_id
			$message_fields[] = 'user_id';
		}
		if(!empty($fields['parent_message']) && !in_array('parent', $message_fields)){
			//如果要获取作者信息，则必须搜出parent
			$message_fields[] = 'parent';
		}
		
		$message = \F::model($model)->fetchRow(array(
			'id = ?'=>$message_id,
			'deleted = 0',
		), $message_fields);
		
		if(!$message){
			return false;
		}
		
		$return = array(
			'message'=>$message,
		);
		//作者信息
		if(!empty($fields['user'])){
			$return['user'] = User::model()->get($message['user_id'], implode(',', $fields['user']));
		}
		
		//父节点
		if(!empty($fields['parent_message'])){
			$parent_message_fields = $fields['parent_message'];
			if(!empty($fields['parent_message_user']) && !in_array('user_id', $parent_message_fields)){
				//如果要获取作者信息，则必须搜出user_id
				$parent_message_fields[] = 'user_id';
			}
				
			$parent_message = \F::model($model)->fetchRow(array(
				'id = ?'=>$message['parent'],
				'deleted = 0',
			), $parent_message_fields);
				
			if($parent_message){
				//有父节点
				$return['parent_message'] = $parent_message;
				if(!empty($fields['parent_message_user'])){
					$return['parent_message_user'] = User::model()->get($parent_message['user_id'], implode(',', $fields['parent_message_user']));
				}
				if(!in_array('user_id', $fields['parent_message']) && in_array('user_id', $parent_message_fields)){
					unset($return['parent_message']['user_id']);
				}
			}else{
				//没有父节点，但是要求返回相关父节点字段，则返回空对象
				$return['parent_message'] = array();
				
				if(!empty($fields['parent_message_user'])){
					$return['parent_message_user'] = array();
				}
			}
		}
		
		//过滤掉那些未指定返回，但出于某些原因先搜出来的字段
		foreach(array('user_id', 'parent') as $f){
			if(!in_array($f, $fields['message']) && in_array($f, $message_fields)){
				unset($return['message'][$f]);
			}
		}
		
		return $return;
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