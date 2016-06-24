<?php
namespace fay\models;

use fay\models\tables\Messages;
use fay\helpers\FieldHelper;
use fay\models\tables\UserCounter;
use fay\models\User;
use fay\core\ErrorException;

class Message extends MultiTree{
	/**
	 * @see MultiTree::$model
	 */
	protected $model = '\fay\models\tables\Messages';
	
	/**
	 * @see MultiTree::$foreign_key
	 */
	protected $foreign_key = 'to_user_id';
	
	/**
	 * @see MultiTree::$field_key
	 */
	protected $field_key = 'message';
	
	/**
	 * @param string $class_name
	 * @return Message
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取一条留言
	 * @param int $message_id 留言ID
	 * @param array|string $fields 返回字段
	 *  - message.*系列可指定messages表返回字段，若有一项为'message.*'，则返回所有字段
	 *  - user.*系列可指定作者信息，格式参照\fay\models\User::get()
	 *  - to_user.*系列可指定被留言用户信息，格式参照\fay\models\User::get()
	 *  - parent.message.*系列可指定父留言messages表返回字段，若有一项为'message.*'，则返回所有字段
	 *  - parent.user.*系列可指定父留言作者信息，格式参照\fay\models\User::get()
	 * @return array
	 */
	public function get($message_id, $fields = array(
		'message'=>array(
			'id', 'content', 'parent', 'create_time',
		),
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
		'to_user'=>array(
			'id', 'nickname',
		),
		'parent'=>array(
			'message'=>array(
				'id', 'content', 'parent', 'create_time',
			),
			'user'=>array(
				'id', 'nickname', 'avatar',
			),
		)
	)){
		$fields = FieldHelper::parse($fields, 'message');
		if(empty($fields['message']) || in_array('*', $fields['message'])){
			//若未指定返回字段，初始化
			$fields['message'] = \F::model($this->model)->getFields(array('status', 'deleted', 'sockpuppet'));
		}
		
		$message_fields = $fields['message'];
		if(!empty($fields['user']) && !in_array('user_id', $message_fields)){
			//如果要获取作者信息，则必须搜出user_id
			$message_fields[] = 'user_id';
		}
		if(!empty($fields['to_user']) && !in_array('to_user_id', $message_fields)){
			//如果要获取被留言用户信息，则必须搜出to_user_id
			$message_fields[] = 'to_user_id';
		}
		if(!empty($fields['parent']) && !in_array('parent', $message_fields)){
			//如果要获取作者信息，则必须搜出parent
			$message_fields[] = 'parent';
		}
		
		$message = \F::model($this->model)->fetchRow(array(
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
			$return['user'] = User::model()->get($message['user_id'], $fields['user']);
		}
		
		//被回复用户信息
		if(!empty($fields['to_user'])){
			$return['to_user'] = User::model()->get($message['to_user_id'], $fields['to_user']);
		}
		
		//父节点
		if(!empty($fields['parent'])){
			$parent_message_fields = $fields['parent']['message'];
			if(!empty($fields['parent']['user']) && !in_array('user_id', $parent_message_fields)){
				//如果要获取作者信息，则必须搜出user_id
				$parent_message_fields[] = 'user_id';
			}
		
			$parent_message = \F::model($this->model)->fetchRow(array(
				'id = ?'=>$message['parent'],
				'deleted = 0',
			), $parent_message_fields);
		
			if($parent_message){
				//有父节点
				$return['parent']['message'] = $parent_message;
				if(!empty($fields['parent']['user'])){
					$return['parent']['user'] = User::model()->get($parent_message['user_id'], $fields['parent']['user']);
				}
				if(!in_array('user_id', $fields['parent']['message']) && in_array('user_id', $parent_message_fields)){
					unset($return['parent']['message']['user_id']);
				}
			}else{
				//没有父节点，但是要求返回相关父节点字段，则返回空数组
				$return['parent']['message'] = array();
		
				if(!empty($fields['parent']['user'])){
					$return['parent']['user'] = array();
				}
			}
		}
		
		//过滤掉那些未指定返回，但出于某些原因先搜出来的字段
		foreach(array('user_id', 'parent', 'to_user_id') as $f){
			if(!in_array($f, $fields['message']) && in_array($f, $message_fields)){
				unset($return['message'][$f]);
			}
		}
		
		return $return;
	}
	
	/**
	 * 判断用户是否对该留言有删除权限
	 * @param int $message 留言
	 *  - 若是数组，视为留言表行记录，必须包含user_id
	 *  - 若是数字，视为留言ID，会根据ID搜索数据库
	 * @param string $action 操作
	 * @param int $user_id 用户ID，若为空，则默认为当前登录用户
	 * @return bool
	 * @throws ErrorException
	 */
	public function checkPermission($message, $action = 'delete', $user_id = null){
		if(!is_array($message)){
			$message = Messages::model()->find($message, 'user_id');
		}
		$user_id || $user_id = \F::app()->current_user;
		
		if(empty($message['user_id'])){
			throw new ErrorException('指定用户留言不存在');
		}
		
		if($message['user_id'] == $user_id){
			//自己的留言总是有权限操作的
			return true;
		}
		
		if(User::model()->isAdmin($user_id) &&
			User::model()->checkPermission('admin/feed-message/' . $action, $user_id)){
			//是管理员，判断权限
			return true;
		}
		
		return false;
	}
	
	/**
	 * 更新留言状态
	 * @param int|array $message_id 留言ID或由留言ID构成的一维数组
	 * @param int $status 状态码
	 * @return int
	 */
	public function setStatus($message_id, $status){
		if(is_array($message_id)){
			return Messages::model()->update(array(
				'status'=>$status,
				'last_modified_time'=>\F::app()->current_time,
			), array('id IN (?)'=>$message_id));
		}else{
			return Messages::model()->update(array(
				'status'=>$status,
				'last_modified_time'=>\F::app()->current_time,
			), $message_id);
		}
	}
	
	/**
	 * 判断一条用户的改变是否需要改变用户留言数
	 * @param array $message 单条留言，必须包含status,sockpuppet字段
	 * @param string $action 操作（可选：delete/undelete/remove/create/approve/disapprove）
	 * @return bool
	 */
	private function needChangeMessages($message, $action){
		$user_message_verify = Option::get('system:user_message_verify');
		if(in_array($action, array('delete', 'remove', 'undelete', 'create'))){
			if($message['status'] == Messages::STATUS_APPROVED || !$user_message_verify){
				return true;
			}
		}else if($action == 'approve'){
			//只要开启了留言审核，则必然在通过审核的时候用户留言数+1
			if($user_message_verify){
				return true;
			}
		}else if($action == 'disapprove'){
			//如果留言原本是通过审核状态，且系统开启了用户留言审核，则当留言未通过审核时，相应用户留言数-1
			if($message['status'] == Messages::STATUS_APPROVED && $user_message_verify){
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * 更user_counter表messages和real_messages字段。
	 * @param array $messages 相关留言（二维数组，每项必须包含to_user_id,status,sockpuppet字段）
	 * @param string $action 操作（可选：delete/undelete/remove/create/approve/disapprove）
	 */
	public function updateMessages($messages, $action){
		$users = array();
		foreach($messages as $m){
			if($this->needChangeMessages($m, $action)){
				//更新留言数
				if(isset($users[$m['to_user_id']]['messages'])){
					$users[$m['to_user_id']]['messages']++;
				}else{
					$users[$m['to_user_id']]['messages'] = 1;
				}
				if(!$m['sockpuppet']){
					//如果不是马甲，更新真实留言数
					if(isset($users[$m['to_user_id']]['real_messages'])){
						$users[$m['to_user_id']]['real_messages']++;
					}else{
						$users[$m['to_user_id']]['real_messages'] = 1;
					}
				}
			}
		}
		
		foreach($users as $to_user_id => $message_count){
			$messages = isset($message_count['messages']) ? $message_count['messages'] : 0;
			$real_messages = isset($message_count['real_messages']) ? $message_count['real_messages'] : 0;
			if(in_array($action, array('delete', 'remove', 'disapprove'))){
				//如果是删除相关的操作，取反
				$messages = - $messages;
				$real_messages = - $real_messages;
			}
			
			if($messages && $messages == $real_messages){
				//如果全部留言都是真实留言，则一起更新real_messages和messages
				UserCounter::model()->incr($to_user_id, array('messages', 'real_messages'), $messages);
			}else{
				if($messages){
					UserCounter::model()->incr($to_user_id, array('messages'), $messages);
				}
				if($real_messages){
					UserCounter::model()->incr($to_user_id, array('real_messages'), $real_messages);
				}
			}
		}
	}
	
	/**
	 * 根据用户ID，以树的形式（体现层级结构）返回留言
	 * @param int $to_user_id 用户ID
	 * @param int $page_size 分页大小
	 * @param int $page 页码
	 * @param string $fields 字段
	 * @return array
	 */
	public function getTree($to_user_id, $page_size = 10, $page = 1, $fields = 'id,content,parent,create_time,user.id,user.nickname,user.avatar'){
		$conditions = array(
			'deleted = 0',
		);
		if(Option::get('system:user_message_verify')){
			//开启了留言审核
			$conditions[] = 'status = '.Messages::STATUS_APPROVED;
		}
		
		return $this->_getTree($to_user_id,
			$page_size,
			$page,
			$fields,
			$conditions
		);
	}
	
	/**
	 * 根据用户ID，以列表的形式（俗称“盖楼”）返回留言
	 * @param int $to_user_id 用户ID
	 * @param int $page_size 分页大小
	 * @param int $page 页码
	 * @param string|array $fields 字段
	 * @return array
	 */
	public function getList($to_user_id, $page_size = 10, $page = 1, $fields = array(
		'message'=>array(
			'id', 'content', 'parent', 'create_time',
		),
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
		'parent'=>array(
			'user'=>array(
				'nickname',
			),
		),
	)){
		$conditions = array(
			't.deleted = 0',
		);
		$join_conditions = array(
			't2.deleted = 0',
		);
		if(Option::get('system:user_message_verify')){
			//开启了留言审核
			$conditions[] = 't.status = '.Messages::STATUS_APPROVED;
			$join_conditions[] = 't2.status = '.Messages::STATUS_APPROVED;
		}
		
		$result = $this->_getList($to_user_id,
			$page_size,
			$page,
			$fields,
			$conditions,
			$join_conditions
		);
		
		return array(
			'messages'=>$result['data'],
			'pager'=>$result['pager'],
		);
	}
	
	/**
	 * 根据用户ID，以列表的形式（俗称“盖楼”）返回留言
	 * @param $parent_id
	 * @param int $page_size 分页大小
	 * @param int $page 页码
	 * @param array|string $fields 字段
	 * @param string $order
	 * @return array
	 * @throws ErrorException
	 */
	public function getChildrenList($parent_id, $page_size = 10, $page = 1, $fields = array(
		'message'=>array(
			'id', 'content', 'parent', 'create_time',
		),
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
		'parent'=>array(
			'user'=>array(
				'nickname',
			),
		),
	), $order = 'ASC'){
		$conditions = array(
			't.deleted = 0',
		);
		$join_conditions = array(
			't2.deleted = 0',
		);
		if(Option::get('system:user_message_verify')){
			//开启了留言审核
			$conditions[] = 't.status = '.Messages::STATUS_APPROVED;
			$join_conditions[] = 't2.status = '.Messages::STATUS_APPROVED;
		}
		
		$result = $this->_getChildrenList($parent_id,
			$page_size,
			$page,
			$fields,
			$conditions,
			$join_conditions,
			$order
		);
		
		return array(
			'messages'=>$result['data'],
			'pager'=>$result['pager'],
		);
	}
	
	/**
	 * 根据用户ID，以二级树的形式（所有对留言的回复不再体现层级结构）返回留言
	 * @param int $user_id 用户ID
	 * @param int $page_size 分页大小
	 * @param int $page 页码
	 * @param string|array $fields 字段
	 * @return array
	 */
	public function getChats($user_id, $page_size = 10, $page = 1, $fields = array(
		'message'=>array(
			'id', 'content', 'parent', 'create_time',
		),
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
	)){
		$conditions = array(
			'deleted = 0',
		);
		if(Option::get('system:user_message_verify')){
			//开启了评论审核
			$conditions[] = 'status = '.Messages::STATUS_APPROVED;
		}
		
		$result = $this->_getChats($user_id,
			$page_size,
			$page,
			$fields,
			$conditions
		);
		
		return array(
			'messages'=>$result['data'],
			'pager'=>$result['pager'],
		);
	}
	
	/**
	 * 获取回复数（不包含回收站里的）
	 * @param int $id
	 */
	public function getReplyCount($id){
		$message = Messages::model()->find($id, 'root,left_value,right_value');
		
		$count = Messages::model()->fetchRow(array(
			'root = ' . $message['root'],
			'left_value > ' . $message['left_value'],
			'right_value < ' . $message['right_value'],
		), 'COUNT(*) AS count');
		return $count['count'];
	}
}