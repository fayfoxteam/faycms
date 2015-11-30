<?php
namespace fay\models\post;

use fay\core\Model;
use fay\models\tables\PostComments;
use fay\core\Exception;
use fay\core\Hook;
use fay\models\Post;
use fay\models\Message;
use fay\helpers\SqlHelper;

class Comment extends Model{
	/**
	 * @return Comment
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 调用fay\models\Message得到的评论，键是message，转成comment返回
	 * @param array $comment 由fay\models\Message得到的消息
	 */
	protected function formatMessageToComment($comment){
		$return = array();
		if(isset($comment['message'])){
			$return['comment'] = $comment['message'];
		}
		if(isset($comment['user'])){
			$return['user'] = $comment['user'];
		}
		if(isset($comment['parent_message'])){
			$return['parent_comment'] = $comment['parent_message'];
		}
		if(isset($comment['parent_message_user'])){
			$return['parent_comment_user'] = $comment['parent_message_user'];
		}
		
		return $return;
	}
	
	/**
	 * 调用fay\models\Message时fields字段传入的是message，将comment转为message
	 * @param string $field 逗号分割的字段
	 */
	protected function formatFieldForMessage($fields){
		//解析$fields
		$fields = SqlHelper::processFields($fields, 'message');
		
		$return = array();
		if(isset($fields['comment'])){
			$return['message'] = $fields['comment'];
		}
		if(isset($fields['user'])){
			$return['user'] = $fields['user'];
		}
		if(isset($fields['parent_comment'])){
			$return['parent_message'] = $fields['parent_comment'];
		}
		if(isset($fields['parent_comment_user'])){
			$return['parent_message_user'] = $fields['parent_comment_user'];
		}
		
		return $return;
	}
	
	/**
	 * 获取一条评论
	 * @param int $comment_id 评论ID
	 * @param int $fields 返回字段
	 *  - comment.*系列可指定post_comments表返回字段，若有一项为'comment.*'，则返回所有字段
	 *  - user.*系列可指定作者信息，格式参照\fay\models\User::get()
	 *  - parent_comment.*系列可指定父评论post_comments表返回字段，若有一项为'comment.*'，则返回所有字段
	 *  - parent_comment_user.*系列可指定父评论作者信息，格式参照\fay\models\User::get()
	 */
	public function get($comment_id, $fields = 'comment.*,user.nickname,user.avatar,parent_comment.content,parent_comment.user_id,parent_comment_user.nickname,parent_comment_user.avatar'){
		$comment = Message::model()->get('fay\models\tables\PostComments', $comment_id, $this->formatFieldForMessage($fields));
		return $this->formatMessageToComment($comment);
	}

	/**
	 * 根据当前节点的父ID得到树根节点 
	 * @param int $id
	 */
	public function getRootByParnetId($parent_id, $fields = 'id,user_id,content,parent'){
		$comment = Message::model()->getRootByParnetId('fay\models\tables\PostComments', $parent_id, $fields);
		
		return $this->formatMessageToComment($comment);
	}
	
	/**
	 * 根据当前节点ID得到父节点
	 * @param int $id
	 */
	public function getParent($comment_id, $fields = 'id,user_id,content,parent'){
		$comment = Message::model()->getParent('fay\models\tables\PostComments', $comment_id, $fields);
		
		return $this->formatMessageToComment($comment);
	}
	
	/**
	 * 根据当前节点的父ID得到父节点
	 * @param int $parent_id 评论ID
	 * @param string $fields 字段
	 */
	public function getParentByParentId($parent_id, $fields = 'id,user_id,content,parent'){
		$comment = Message::model()->getParentByParentId('fay\models\tables\PostComments', $parent_id, $fields);
		
		return $this->formatMessageToComment($comment);
	}
	
	/**
	 * 根据当前节点ID得到树根节点（直接评论文章的评论）
	 * @param int $comment_id
	 */
	public function getRoot($comment_id, $fields = 'id,user_id,content,parent'){
		$comment = Message::model()->getRoot('fay\models\tables\PostComments', $comment_id, $fields);
		
		return $this->formatMessageToComment($comment);
	}
	
	/**
	 * 发表一条文章评论
	 * @param int $post_id 文章ID
	 * @param string $content 评论内容
	 * @param int $parent 父ID，若是回复评论的评论，则带上被评论的评论ID，默认为0
	 * @param int $status 状态（默认为待审核）
	 * @param int $user_id 用户ID，若不指定，默认为当前登录用户ID
	 */
	public function create($post_id, $content, $parent = 0, $status = PostComments::STATUS_PENDING, $user_id = null, $is_real = true){
		$user_id === null && $user_id = \F::app()->current_user;
		
		if(!Post::isPostIdExist($post_id)){
			throw new Exception('文章ID不存在', 'post_id-not-exist');
		}
		
		$comment_id = Message::model()->create('fay\models\tables\PostComments', 'post_id', $post_id, $content, $status, $parent, $user_id, $is_real);
		
		//执行钩子
		Hook::getInstance()->call('after_post_comment_created', array(
			'comment_id'=>$comment_id,
		));
		
		return $comment_id;
	}
	
	/**
	 * 判断当前登录用户是否对该文章有删除权限
	 * @param int $comment_id 文章ID
	 */
	public function checkPermission($comment_id, $action = 'delete'){
		if(substr(\F::config()->get('session_namespace'), -6) == '_admin'){
			//后台用户
			//没有删除权限，直接返回错误
			if(!\F::app()->checkPermission('admin/post-comment/' . $action)){
				return false;
			}
			return true;
		}else{
			//前台用户，只能操作自己的评论
			$comment = PostComments::model()->find($comment_id, 'user_id');
			if($comment && $comment['user_id'] != \F::app()->current_user){
				return false;
			}else{
				return true;
			}
		}
	}
	
	/**
	 * 软删除一条评论
	 * 软删除不会修改parent标识，因为删除的东西随时都有可能会被恢复，而parent如果变了是无法被恢复的。
	 * @param int $comment_id 评论ID
	 */
	public function delete($comment_id){
		$comment = PostComments::model()->find($comment_id, 'id,parent,deleted,is_terminal');
		if(!$comment || $comment['deleted']){
			throw new Exception('指定评论ID不存在');
		}
		Message::model()->delete('fay\models\tables\PostComments', $comment);
		
		//执行钩子
		Hook::getInstance()->call('after_post_comment_deleted', array(
			'comment_id'=>$comment_id,
		));
	}
	
	/**
	 * 从回收站恢复一条评论
	 * @param int $comment_id 评论ID
	 */
	public function undelete($comment_id){
		$comment = PostComments::model()->find($comment_id, 'id,parent,deleted,is_terminal');
		if(!$comment){
			throw new Exception('指定评论ID不存在');
		}
		if(!$comment['deleted']){
			throw new Exception('指定评论ID不在已删除状态');
		}
		Message::model()->undelete('fay\models\tables\PostComments', $comment);
		
		//执行钩子
		Hook::getInstance()->call('after_post_comment_deleted', array(
			'comment_id'=>$comment_id,
		));
	}
	
	/**
	 * 将一条直接回复文章的评论及其所有回复标记为已删除
	 * @param int $comment_id 评论ID。必须是直接评论文章的评论（根评论）
	 */
	public function deleteChat($comment_id){
		$comment = PostComments::model()->find($comment_id, 'id,parent');
		if(!$comment){
			throw new Exception('指定评论ID不存在');
		}
		
		if($comment['parent']){
			throw new Exception('fay\models\post\Comment::deleteChat方法$comment_id参数必须是直接评论文章的评论ID');
		}
		
		return Message::model()->deleteChat('fay\models\tables\PostComments', $comment_id);
	}
	
	/**
	 * 永久删除一条评论
	 * @param int $comment_id 评论ID
	 */
	public function remove($comment_id){
		$comment = PostComments::model()->find($comment_id, 'id,parent,is_terminal');
		if(!$comment){
			throw new Exception('指定评论ID不存在');
		}
		
		//执行钩子，这个不能用after，记录都没了就没法找了
		Hook::getInstance()->call('before_post_comment_removed', array(
			'comment_id'=>$comment_id,
		));
		
		return Message::model()->remove('fay\models\tables\PostComments', $comment);
	}
	
	/**
	 * 删除一条直接回复文章的评论及其所有回复
	 * @param int $comment_id 评论ID。必须是直接评论文章的评论（根评论）
	 */
	public function removeChat($comment_id){
		$comment = PostComments::model()->find($comment_id, 'id,parent');
		if(!$comment){
			throw new Exception('指定评论ID不存在');
		}
		
		if($comment['parent']){
			throw new Exception('fay\models\post\Comment::removeChat方法$comment_id参数必须是直接评论文章的评论ID');
		}
		
		return Message::model()->removeChat('fay\models\tables\PostComments', $comment_id);;
	}
	
	/**
	 * 更新评论状态
	 * @param int $comment_id 评论ID
	 * @param int $status 状态码
	 */
	public function setStatus($comment_id, $status){
		return PostComments::model()->update(array(
			'status'=>$status,
		), $comment_id);
	}
	
	/**
	 * 通过审核
	 * @param int $comment_id 评论ID
	 */
	public function approved($comment_id){
		return $this->setStatus($comment_id, PostComments::STATUS_APPROVED);
	}
	
	/**
	 * 不通过审核
	 * @param int $comment_id 评论ID
	 */
	public function unapproved($comment_id){
		return $this->setStatus($comment_id, PostComments::STATUS_UNAPPROVED);
	}
	
	/**
	 * 编辑一条评论（只能编辑评论内容部分）
	 * @param int $comment_id 评论ID
	 * @param string $content 评论内容
	 */
	public function update($comment_id, $content){
		return PostComments::model()->update(array(
			'content'=>$content,
		), $comment_id);
	}
}