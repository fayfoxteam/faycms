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
			$fields['message'] = \F::model($model)->getFields(array('status', 'deleted', 'is_real'));
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
				
			$return['parent_message'] = $parent_message;
				
			if($parent_message){
				if(!empty($fields['parent_message_user'])){
					$return['parent_message_user'] = User::model()->get($parent_message['user_id'], implode(',', $fields['parent_message_user']));
				}
				if(!in_array('user_id', $fields['parent_message']) && in_array('user_id', $parent_message_fields)){
					unset($return['parent_message']['user_id']);
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
	 * 根据当前节点的父ID得到树根节点
	 * @param string $model 表模型
	 * @param int $parent_id
	 * @param int $fields 返回字段，同get方法的fields格式
	 */
	public function getRootByParnetId($model, $parent_id, $fields = 'id,user_id,content,parent'){
		if($parent_id == 0){
			//已经是根节点
			return false;
		}
		$parent_message = $this->get($model, $parent_id, $fields);
		if($parent_message['message']['parent'] != 0){
			$parent_message = $this->getRootByParnetId($model, $parent_message['message']['parent'], $fields);
		}
		return $parent_message;
	}
	
	/**
	 * 根据当前节点ID得到父节点（若无父节点直接返回本节点）
	 * @param string $model 表模型
	 * @param int $id
	 * @param int $fields 返回字段，同get方法的fields格式
	 */
	public function getParent($model, $message_id, $fields = 'id,user_id,content,parent'){
		$message = $this->get($model, $message_id, $fields);
		if($message['message']['parent'] == 0){
			//无父节点直接返回本节点
			return $message;
		}else{
			return $this->get($model, $message['message']['parent'], $fields);
		}
	}
	
	/**
	 * 根据当前节点ID得到树根节点
	 * @param string $model 表模型
	 * @param int $message_id
	 * @param int $fields 返回字段，同get方法的fields格式
	 */
	public function getRoot($model, $message_id, $fields = 'id,user_id,content,parent'){
		$parent_message = $this->getParent($model, $message_id, $fields);
		if($parent_message['parent'] != 0){
			return $this->getRootByParnetId($model, $parent_message['message']['parent'], $fields);
		}else{
			return $parent_message;
		}
	}
	
	/**
	 * 发表一条消息
	 * @param string $model 表模型
	 * @param string $target_field 目标字段名（例如post_id）
	 * @param int $target_id 目标ID
	 * @param string $content 消息内容
	 * @param int $status 状态
	 * @param int $parent 父ID，若是回复消息的消息，则带上被回复消息的消息ID，默认为0
	 * @param int $user_id 用户ID，若不指定，默认为当前登录用户ID
	 * @param bool $is_real 是否是真实用户行为
	 */
	public function create($model, $target_field, $target_id, $content, $status, $parent = 0, $user_id = null, $is_real = true){
		$user_id === null && $user_id = \F::app()->current_user;
		
		$root_node = $this->getRootByParnetId($model, $parent, 'id,parent');
		if($root_node){
			$root = $root_node['message']['id'];
		}else{
			$root = 0;
		}
		$message_id = \F::model($model)->insert(array(
			$target_field=>$target_id,
			'user_id'=>$user_id,
			'content'=>$content,
			'parent'=>$parent,
			'root'=>$root,
			'create_time'=>\F::app()->current_time,
			'status'=>$status,
			'deleted'=>0,
			'is_terminal'=>1,
			'is_real'=>$is_real ? 1 : 0,
		));
		if(!empty($parent)){
			$parent_message = \F::model($model)->find($parent, 'is_terminal');
			if($parent_message['is_terminal']){
				//标记其父节点为非叶子节点
				\F::model($model)->update(array(
					'is_terminal'=>0,
				), $parent);
			}
		}
		
		return $message_id;
	}
	
	/**
	 * 软删除一条消息
	 * 软删除不会修改is_terminal和parent标识，因为删除的东西随时都有可能会被恢复，而parent如果变了是无法被恢复的。
	 * @param string $model 表模型
	 * @param int $message_id 消息ID
	 */
	public function delete($model, $message){
		if(!is_array($message)){
			$message = \F::model($model)->find($message, 'id,parent,deleted,is_terminal');
		}
		
		if($message['deleted']){
			//消息已被删除，返回0（受影响行数）
			return 0;
		}
		
		/*
		 * 查找其父节点，若父节点未被删除，且不是叶子节点，并且没有其他子节点，则将父节点更新为叶子节点。
		 * 不管当前节点是不是叶子节点，都要确认父节点。
		 * 因为即便该节点还有子节点，当该节点被删除时，子节点被显示到根节点，但并不更新parent字段
		 * （与wordpress处理方式一致）
		 */
		$parent_message = \F::model($model)->fetchRow(array(
			'id = ?'=>$message['parent'],
			'deleted = 0',
		), 'id,is_terminal');
		if($parent_message && !$parent_message['is_terminal']){
			if(!\F::model($model)->fetchRow(array(
				'parent = ' . $message['parent'],
				'deleted = 0',
				'id != ' . $message['id'],
			), 'id')){
				//其父节点没有其他子节点，将其父节点标记为叶子节点
				\F::model($model)->update(array(
					'is_terminal'=>1,
				), $message['parent']);
			}
		}
		
		return \F::model($model)->update(array(
			'deleted'=>1,
		), $message['id']);
	}
	
	public function undelete($model, $message){
		if(!is_array($message)){
			$message = \F::model($model)->find($message, 'id,parent,deleted,is_terminal');
		}
		
		if(!$message['deleted']){
			//消息未被删除，返回0（受影响行数）
			return 0;
		}
		
		/*
		 * 若存在父节点，且父节点原本是叶子节点，则将父节点更新为非叶子结点
		 * 若父节点已被删除则不去update
		 */
		if($message['parent']){
			$parent_message = \F::model($model)->fetchRow(array(
				'id = ?'=>$message['parent'],
				'deleted = 0',
			), 'id,is_terminal');
			if($parent_message['is_terminal']){
				\F::model($model)->update(array(
					'is_terminal'=>0,
				), $parent_message['id']);
			}
		}
		
		if(!$message['is_terminal'] && !\F::model($model)->fetchRow(array(
			'parent = ' . $message['id'],
			'deleted = 0',
		), 'id')){
			/*
			 * 若当前节点被标记为非叶子节点，且不存在子节点（当前节点被删除后子节点又被删除）
			 * 则将当前节点置为叶子节点
			 */
			return \F::model($model)->update(array(
				'is_terminal'=>1,
				'deleted'=>0,
			), $message['id']);
		}else if($message['is_terminal'] && \F::model($model)->fetchRow(array(
			'parent = ' . $message['id'],
			'deleted = 0',
		), 'id')){
			/*
			 * 若当前节点被标记为叶子节点，但存在子节点（当前节点被删除后子节点又被还原）
			 * 则将当前节点置为非叶子节点
			 */
			return \F::model($model)->update(array(
				'is_terminal'=>0,
				'deleted'=>0,
			), $message['id']);
		}else{
			//否则仅更新deleted字段
			return \F::model($model)->update(array(
				'deleted'=>0,
			), $message['id']);
		}
	}
	
	/**
	 * 将一条直接回复文章的消息及其所有回复标记为已删除
	 * @param string $model 表模型
	 * @param int $message_id 消息ID。必须是直接回复目标的消息（根消息）
	 */
	public function deleteChat($model, $message_id){
		return \F::model($model)->update(array(
			'deleted'=>1,
		), array(
			'or'=>array(
				'id = ?'=>$message_id,
				'root = ?'=>$message_id,
			)
		));
	}
	
	/**
	 * 永久删除一条消息
	 * @param string $model 表模型
	 * @param int|array $message 消息
	 *  - 若是数字，视为消息ID
	 *  - 若是数字，视为已包含足够信息的消息数组，至少需要包含id, parent, is_terminal
	 */
	public function remove($model, $message){
		if(!is_array($message)){
			$message = \F::model($model)->find($message, 'id,parent,is_terminal');
		}
		
		if($message['is_terminal']){
			//是叶子节点，查找其父节点还有没有其他子节点
			if(!\F::model($model)->fetchRow(array(
				'parent = ' . $message['parent'],
				'deleted = 0',
				'id != ' . $message['id'],
			), 'id')){
				//其父节点没有其他子节点，将其父节点标记为叶子节点
				\F::model($model)->update(array(
					'is_terminal'=>1,
				), $message['parent']);
			}
		}else{
			//不是叶子节点，将其子节点挂到其父节点上
			\F::model($model)->update(array(
				'parent'=>$message['parent'],
			), array(
				'parent=?'=>$message,
			));
		}
		
		return \F::model($model)->delete($message['id']);
	}
	
	/**
	 * 删除一条直接回复文章的评论及其所有回复
	 * @param int $message_id 评论ID。必须是直接评论文章的评论（根评论）
	 */
	public function removeChat($model, $message_id){
		return \F::model($model)->delete(array(
			'or'=>array(
				'id = ?'=>$message_id,
				'root = ?'=>$message_id,
			)
		));
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