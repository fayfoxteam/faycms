<?php
namespace fay\services;

use fay\core\Model;
use fay\models\tables\Messages;
use fay\core\Exception;
use fay\core\Hook;
use fay\models\Post;
use fay\models\Message as MessageModel;
use fay\models\Option;
use fay\helpers\ArrayHelper;
use fay\helpers\Request;
use fay\models\tables\UserCounter;
use fay\models\User;

/**
 * 留言服务
 */
class Message extends Model{
	/**
	 * @param string $class_name
	 * @return Message
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 发表一条用户留言
	 * @param int $to_user_id 用户ID
	 * @param string $content 评论内容
	 * @param int $parent 父ID，若是回复评论的评论，则带上被评论的评论ID，默认为0
	 * @param int $status 状态（默认为待审核）
	 * @param array $extra 扩展参数，二次开发时可能会用到
	 * @param int $user_id 用户ID，若不指定，默认为当前登录用户ID
	 * @param int $sockpuppet 马甲信息，若是真实用户，传入0，默认为0
	 * @return int 消息ID
	 * @throws Exception
	 */
	public function create($to_user_id, $content, $parent = 0, $status = Messages::STATUS_PENDING, $extra = array(), $user_id = null, $sockpuppet = 0){
		$user_id === null && $user_id = \F::app()->current_user;
		
		if(!User::isUserIdExist($to_user_id)){
			throw new Exception('用户ID不存在', 'to_user_id-not-exist');
		}
		
		if($parent){
			$parent_message = Messages::model()->find($parent, 'to_user_id,deleted');
			if(!$parent_message || $parent_message['deleted']){
				throw new Exception('父节点不存在', 'parent-not-exist');
			}
		}
		
		$message_id = MessageModel::model()->create(array_merge($extra, array(
			'to_user_id'=>$to_user_id,
			'content'=>$content,
			'status'=>$status,
			'user_id'=>$user_id,
			'sockpuppet'=>$sockpuppet,
			'create_time'=>\F::app()->current_time,
			'last_modified_time'=>\F::app()->current_time,
			'ip_int'=>Request::ip2int(\F::app()->ip),
		)), $parent);
		
		//更新用户留言数
		MessageModel::model()->updateMessages(array(array(
			'to_user_id'=>$to_user_id,
			'status'=>$status,
			'sockpuppet'=>$sockpuppet,
		)), 'create');
		
		//执行钩子
		Hook::getInstance()->call('after_post_message_created', array(
			'message_id'=>$message_id,
		));
		
		return $message_id;
	}
	
	/**
	 * 软删除一条评论
	 * 软删除不会修改parent标识，因为删除的东西随时都有可能会被恢复，而parent如果变了是无法被恢复的。
	 * @param int $message_id 评论ID
	 * @throws Exception
	 */
	public function delete($message_id){
		$message = Messages::model()->find($message_id, 'deleted,to_user_id,status,sockpuppet');
		if(!$message){
			throw new Exception('指定评论ID不存在', 'message_id-is-not-exist');
		}
		if($message['deleted']){
			throw new Exception('评论已删除', 'message-already-deleted');
		}
		
		//软删除不需要动树结构，只要把deleted字段标记一下即可
		Messages::model()->update(array(
			'deleted'=>1,
			'last_modified_time'=>\F::app()->current_time,
		), $message_id);
		
		//更新用户留言数
		MessageModel::model()->updateMessages(array($message), 'delete');
		
		//执行钩子
		Hook::getInstance()->call('after_post_message_deleted', array(
			'message_id'=>$message_id,
		));
	}
	
	/**
	 * 批量删除
	 * @param array $message_ids 由评论ID构成的一维数组
	 * @return int|null
	 */
	public function batchDelete($message_ids){
		$messages = Messages::model()->fetchAll(array(
			'id IN (?)'=>$message_ids,
			'deleted = 0',
		), 'id,to_user_id,sockpuppet,status');
		if(!$messages){
			//无符合条件的记录
			return 0;
		}
		
		//更新状态
		$affected_rows = Messages::model()->update(array(
			'deleted'=>1,
			'last_modified_time'=>\F::app()->current_time,
		), array(
			'id IN (?)'=>$message_ids,
		));
		
		//更新用户留言数
		MessageModel::model()->updateMessages($messages, 'delete');
		
		foreach($messages as $c){
			//执行钩子（循环逐条执行）
			Hook::getInstance()->call('after_post_message_deleted', array(
				'message_id'=>$c['id'],
			));
		}
		
		return $affected_rows;
	}
	
	/**
	 * 从回收站恢复一条评论
	 * @param int $message_id 评论ID
	 * @throws Exception
	 */
	public function undelete($message_id){
		$message = Messages::model()->find($message_id, 'deleted,to_user_id,status,sockpuppet');
		if(!$message){
			throw new Exception('指定评论ID不存在', 'message_id-is-not-exist');
		}
		if(!$message['deleted']){
			throw new Exception('指定评论ID不在回收站中', 'message-not-in-recycle-bin');
		}
		
		//还原不需要动树结构，只是把deleted字段标记一下即可
		Messages::model()->update(array(
			'deleted'=>0,
			'last_modified_time'=>\F::app()->current_time,
		), $message_id);
		
		//更新用户留言数
		MessageModel::model()->updateMessages(array($message), 'undelete');
		
		//执行钩子
		Hook::getInstance()->call('after_post_message_undeleted', array(
			'message_id'=>$message_id,
		));
	}
	
	/**
	 * 批量还原
	 * @param array $message_ids 由评论ID构成的一维数组
	 * @return int|null
	 */
	public function batchUnelete($message_ids){
		$messages = Messages::model()->fetchAll(array(
			'id IN (?)'=>$message_ids,
			'deleted > 0',
		), 'id,to_user_id,sockpuppet,status');
		if(!$messages){
			//无符合条件的记录
			return 0;
		}
		
		//更新状态
		$affected_rows = Messages::model()->update(array(
			'deleted'=>0,
			'last_modified_time'=>\F::app()->current_time,
		), array(
			'id IN (?)'=>$message_ids,
		));
		
		//更新用户留言数
		MessageModel::model()->updateMessages($messages, 'undelete');
		
		foreach($messages as $c){
			//执行钩子（循环逐条执行）
			Hook::getInstance()->call('after_post_message_undeleted', array(
				'message_id'=>$c['id'],
			));
		}
		
		return $affected_rows;
	}
	
	/**
	 * 删除一条评论及所有回复该评论的评论
	 * @param int $message_id 评论ID
	 * @return array
	 * @throws Exception
	 */
	public function deleteAll($message_id){
		$message = Messages::model()->find($message_id, 'left_value,right_value,root');
		if(!$message){
			throw new Exception('指定评论ID不存在');
		}
		
		//获取所有待删除节点
		$messages = Messages::model()->fetchAll(array(
			'root = ?'=>$message['root'],
			'left_value >= ' . $message['left_value'],
			'right_value <= ' . $message['right_value'],
			'deleted = 0',
		), 'id,to_user_id,status,sockpuppet');
		
		if($messages){
			//如果存在待删除节点，则执行删除
			$message_ids = ArrayHelper::column($messages, 'id');
			Messages::model()->update(array(
				'deleted'=>1,
				'last_modified_time'=>\F::app()->current_time,
			), array(
				'id IN (?)'=>$message_ids,
			));
			
			//更新用户留言数
			MessageModel::model()->updateMessages($messages, 'delete');
			
			//执行钩子
			Hook::getInstance()->call('after_post_message_batch_deleted', array(
				'message_ids'=>$message_ids,
			));
			
			return $message_ids;
		}else{
			return array();
		}
	}
	
	/**
	 * 永久删除一条评论
	 * @param int $message_id 评论ID
	 * @return bool
	 * @throws Exception
	 */
	public function remove($message_id){
		$message = Messages::model()->find($message_id, '!content');
		if(!$message){
			throw new Exception('指定评论ID不存在');
		}
		
		//执行钩子，这个不能用after，记录都没了就没法找了
		Hook::getInstance()->call('before_post_message_removed', array(
			'message_id'=>$message_id,
		));
		
		MessageModel::model()->remove($message);
		
		if(!$message['deleted']){
			//更新用户留言数
			MessageModel::model()->updateMessages(array($message), 'remove');
		}
		
		return true;
	}
	
	/**
	 * 物理删除一条评论及所有回复该评论的评论
	 * @param int $message_id 评论ID
	 * @return array
	 * @throws Exception
	 */
	public function removeAll($message_id){
		$message = Messages::model()->find($message_id, '!content');
		if(!$message){
			throw new Exception('指定评论ID不存在');
		}
		
		//获取所有待删除节点
		$messages = Messages::model()->fetchAll(array(
			'root = ?'=>$message['root'],
			'left_value >= ' . $message['left_value'],
			'right_value <= ' . $message['right_value'],
		), 'id,to_user_id,status,sockpuppet');
		$message_ids = ArrayHelper::column($messages, 'id');
		//执行钩子
		Hook::getInstance()->call('before_post_message_batch_removed', array(
			'message_ids'=>$message_ids,
		));
		
		//获取所有不在回收站内的节点（已删除的显然不需要再更新评论数了）
		$undeleted_messages = array();
		foreach($message as $c){
			if(!$c['deleted']){
				$undeleted_messages[] = $c;
			}
		}
		//更新用户留言数
		MessageModel::model()->updateMessages($undeleted_messages, 'remove');
		
		//执行删除
		MessageModel::model()->removeAll($message);
		
		return $message_ids;
	}
	
	/**
	 * 通过审核
	 * @param int $message_id 评论ID
	 * @return bool
	 * @throws Exception
	 */
	public function approve($message_id){
		$message = Messages::model()->find($message_id, '!content');
		if(!$message){
			throw new Exception('指定评论ID不存在', 'message_id-is-not-exist');
		}
		if($message['deleted']){
			throw new Exception('评论已删除', 'message-deleted');
		}
		if($message['status'] == Messages::STATUS_APPROVED){
			throw new Exception('已通过审核，请勿重复操作', 'already-approved');
		}
		
		MessageModel::model()->setStatus($message_id, Messages::STATUS_APPROVED);
		
		//更新用户留言数
		MessageModel::model()->updateMessages(array($message), 'approve');
		
		//执行钩子
		Hook::getInstance()->call('after_post_message_approved', array(
			'message_id'=>$message_id,
		));
		return true;
	}
	
	/**
	 * 批量通过审核
	 * @param array $message_ids 由评论ID构成的一维数组
	 * @return int
	 */
	public function batchApprove($message_ids){
		$messages = Messages::model()->fetchAll(array(
			'id IN (?)'=>$message_ids,
			'status != ' . Messages::STATUS_APPROVED,
		), 'id,to_user_id,sockpuppet,status');
		if(!$messages){
			//无符合条件的记录
			return 0;
		}
		
		//更新状态
		$affected_rows = MessageModel::model()->setStatus(ArrayHelper::column($messages, 'id'), Messages::STATUS_APPROVED);
		
		//更新用户留言数
		MessageModel::model()->updateMessages($messages, 'approve');
		
		foreach($messages as $c){
			//执行钩子（循环逐条执行）
			Hook::getInstance()->call('after_post_message_approved', array(
				'message_id'=>$c['id'],
			));
		}
		
		return $affected_rows;
	}
	
	/**
	 * 不通过审核
	 * @param int $message_id 评论ID
	 * @return bool
	 * @throws Exception
	 */
	public function disapprove($message_id){
		$message = Messages::model()->find($message_id, '!content');
		if(!$message){
			throw new Exception('指定评论ID不存在', 'message_id-is-not-exist');
		}
		if($message['deleted']){
			throw new Exception('评论已删除', 'message-is-deleted');
		}
		if($message['status'] == Messages::STATUS_UNAPPROVED){
			throw new Exception('该评论已是“未通过审核”状态，请勿重复操作', 'already-unapproved');
		}
		
		MessageModel::model()->setStatus($message_id, Messages::STATUS_UNAPPROVED);
		
		//更新用户留言数
		MessageModel::model()->updateMessages(array($message), 'disapprove');
		
		//执行钩子
		Hook::getInstance()->call('after_post_message_disapproved', array(
			'message_id'=>$message_id,
		));
		return true;
	}
	
	/**
	 * 批量不通过审核
	 * @param array $message_ids 由评论ID构成的一维数组
	 * @return int
	 */
	public function batchDisapprove($message_ids){
		$messages = Messages::model()->fetchAll(array(
			'id IN (?)'=>$message_ids,
			'status != ' . Messages::STATUS_UNAPPROVED,
		), 'id,to_user_id,sockpuppet,status');
		if(!$messages){
			//无符合条件的记录
			return 0;
		}
		
		//更新状态
		$affected_rows = MessageModel::model()->setStatus(ArrayHelper::column($messages, 'id'), Messages::STATUS_UNAPPROVED);
		
		//更新用户留言数
		MessageModel::model()->updateMessages($messages, 'disapprove');
		
		foreach($messages as $c){
			//执行钩子（循环逐条执行）
			Hook::getInstance()->call('after_post_message_disapproved', array(
				'message_id'=>$c['id'],
			));
		}
		
		return $affected_rows;
	}
	
	/**
	 * 编辑一条评论（只能编辑评论内容部分）
	 * @param int $message_id 评论ID
	 * @param string $content 评论内容
	 * @return int|null
	 */
	public function update($message_id, $content){
		return Messages::model()->update(array(
			'content'=>$content,
		), $message_id);
	}
}