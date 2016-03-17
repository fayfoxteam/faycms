<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Messages;
use fay\core\Sql;

class Message extends Model{
	/**
	 * @param string $class_name
	 * @return Message
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 发表评论/留言
	 */
	public function create($target, $content, $type, $parent = 0, $status = Messages::STATUS_APPROVED, $user_id = null){
		$root_node = $this->getRootByParnetId($parent);
		if($root_node){
			$root = $root_node['id'];
		}else{
			$root = 0;
		}
		$data = array(
			'user_id'=>$user_id === null ? \F::app()->current_user : $user_id,
			'target'=>$target,
			'content'=>$content,
			'parent'=>$parent,
			'root'=>$root,
			'type'=>$type,
			'create_time'=>\F::app()->current_time,
			'status'=>$status,
			'deleted'=>0,
			'is_terminal'=>1,
		);
		$message_id = Messages::model()->insert($data);
		if(!empty($parent)){
			//标记其父节点为非叶子节点
			Messages::model()->update(array(
				'is_terminal'=>0,
			), $parent);
		}
		return $message_id;
	}
	
	/**
	 *获取指定parent下的所有直接子节点的信息
	 */
	public function getNextLevel($parent_id = 0, $fields = '*'){
		//返回指定parent下的所有直接子节点的信息
		$sql = new Sql();
		return $sql->from(array('m'=>'messages'), $fields)
			->joinLeft(array('u'=>'users'), 'm.user_id = u.id', 'username,nickname,realname,avatar')
			->where("m.parent = {$parent_id}")
			->fetchAll();
	}
	
	
	public function get($id, $fields = '*'){
		$sql = new Sql();
		return $sql->from(array('m'=>'messages'), $fields)
			->joinLeft(array('u'=>'users'), 'm.user_id = u.id', 'username,nickname,realname,avatar')
			->joinLeft(array('m2'=>'messages'), 'm.parent = m2.id', 'content AS parent_content,user_id AS parent_user_id')
			->joinLeft(array('u2'=>'users'), 'm2.user_id = u2.id', 'username AS parent_username,realname AS parent_realname,nickname AS parent_nickname')
			->where("m.id = {$id}")
			->fetchRow();
	}
	
	/**
	 * 根据留言对象和类型
	 * 获取所有留言，一维数组方式返回<br>
	 * （例如获取一篇文章的所有评论）
	 * @param int $target
	 * @param int $type
	 * @param string $fields
	 */
	public function getAll($target, $type, $fields = '*'){
		$sql = new Sql();
		return $sql->from(array('m'=>'messages'), $fields)
			->joinLeft(array('u'=>'users'), 'm.user_id = u.id', 'username,nickname,realname,avatar')
			->joinLeft(array('m2'=>'messages'), 'm.parent = m2.id', 'content AS parent_content,user_id AS parent_user_id')
			->joinLeft(array('u2'=>'users'), 'm2.user_id = u2.id', 'username AS username,realname AS parent_realname,nickname AS parent_nickname')
			->where(array(
				"m.target = {$target}",
				"m.type = {$type}",
				'm.deleted = 0',
			))
			->order('create_time DESC')
			->fetchAll();
	}
	
	/**
	 * 获取所有留言，并以树形方式返回
	 * @param int $target 目标ID，如文章ID
	 * @param int $type 类型
	 * @param string $fields
	 */
	public function getTree($target, $type, $fields = '*'){
		$has_parent = true;
		$has_is_terminal = true;
		$has_id = true;
		if($fields != '*'){//如果fields本身没包含id,parent和is_terminal字段，强制加入这三个字段
			$fields = explode(',', $fields);
			if(!in_array('parent', $fields)){
				$fields[] = 'parent';
				$has_parent = false;
			}
			if(!in_array('is_terminal', $fields)){
				$fields[] = 'is_terminal';
				$has_is_terminal = false;
			}
			if(!in_array('id', $fields)){
				$fields[] = 'id';
				$has_id = false;
			}
			$fields = implode(',', $fields);
		}
		$messages = $this->getAll($target, $type, $fields);
		return $this->renderTree($messages, 0, $has_id, $has_parent, $has_is_terminal);
	}
	
	/**
	 * 将数据库中搜出来的一维数组，构造成为一棵树
	 * @param array $messages
	 * @param int $parent
	 */
	private function renderTree(&$messages, $parent = 0, $has_id = true, $has_parent = true, $has_is_terminal = true){
		$tree = array();
		if(empty($messages)) return $tree;
		
		foreach($messages as $k => $m){
			if($m['parent'] == $parent){
				if(!$has_parent) unset($m['parent']);//参数中不要求返回本字段，故删除
				$tree[] = $m;
				unset($messages[$k]);
			}
		}
		foreach($tree as &$t){
			if(!$t['is_terminal']){
				$t['child'] = $this->renderTree($messages, $t['id'], $has_id, $has_parent, $has_is_terminal);
			}
			if(!$has_is_terminal) unset($t['is_terminal']);//参数中不要求返回本字段，故删除
			if(!$has_id) unset($t['id']);//参数中不要求返回本字段，故删除
		}
		if($parent == 0){
			if(!empty($messages)){
				//若有树枝节点在回收站中，则会出现叶子节点无处挂靠的情况
				//这些叶子节点全部挂靠到根节点，且体现父子关系（wordpress是这么做的）
				$tree = $tree + $messages;
			}
		}
		return $tree;
	}
	
	/**
	 * 如果是叶子节点被删除，则正常删除，再判断是否还有兄弟节点，没有，则将其父节点标记为叶子节点
	 * 如果不是叶子节点被删除，则被删除节点的子节点会挂到其父节点上
	 * @param int $id
	 * @return boolean
	 */
	public function remove($id){
		//根据主键查询数据
		$msg = Messages::model()->find($id);
		//var_dump($msg);
		//删除该条记录
		Messages::model()->delete($id);
		if($msg['is_terminal']){
			//是叶子节点，查找其父节点还有没有其他子节点
			if(Messages::model()->fetchRow(array(
				'parent = ?'=>$msg['parent'],
			), 'id')){
				//其父节点没有其他子节点，将其父节点标记为叶子节点
				Messages::model()->update(array(
					'is_terminal'=>1,
				), $msg['parent']);
			}
		}else{
			//不是叶子节点，将其子节点挂到其父节点上
			Messages::model()->update(array(
				'parent'=>$msg['parent'],
			), array(
				'parent=?'=>$id,
			));
		}
		return true;
	}
	
	/**
	 * 从根开始，完整的删除一个会话的所有留言
	 * @param int $id
	 */
	public function removeChat($id){
		$message = $this->get($id, 'id,root');
		if($message['root'] != 0){
			//传过来的id并不是根节点
			return false;
		}
		
		return Messages::model()->delete(array(
			'or'=>array(
				'id = '.$message['id'],
				'root = '.$message['id'],
			)
		));
	}
	
	/**
	 * 根据当前节点ID得到父节点
	 * @param int $id
	 */
	public function getParent($id){
		$message = Messages::model()->find($id);
		if($message['parent'] == 0){//无父节点直接返回本节点
			return $message;
		}else{
			return $this->get($message['parent']);
		}
	}
	
	/**
	 * 根据当前节点的父ID得到父节点
	 * @param int $id
	 */
	public function getParentByParentId($id){
		if($id == 0){return false;}//根节点无父节点
		return $this->get($id);
	}
	
	/**
	 * 根据当前节点ID得到树根节点
	 * @param int $id
	 */
	public function getRoot($id){
		$parent_message = $this->getParent($id);
		if($parent_message['parent'] != 0){
			return $this->getRootByParnetId($parent_message['parent']);
		}else{
			return $parent_message;
		}
	}

	/**
	 * 根据当前节点的父ID得到树根节点
	 * @param int $id
	 */
	public function getRootByParnetId($id){
		if($id == 0){return false;}//已经是根节点
		$parent_message = $this->getParentByParentId($id);
		if($parent_message['parent'] != 0){
			$parent_message = $this->getRootByParnetId($parent_message['parent']);
		}
		return $parent_message;
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