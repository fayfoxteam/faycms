<?php
namespace fay\models\post;

use fay\core\Model;
use fay\models\tables\PostComments;
use fay\core\Exception;
use fay\models\User;
use fay\helpers\SqlHelper;
use fay\core\Hook;
use fay\models\Post;

class Comment extends Model{
	/**
	 * @return Comment
	 */
	public static function model($className = __CLASS__){
		return parent::model($className);
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
		//解析$fields
		$fields = SqlHelper::processFields($fields, 'comment');
		if(empty($fields['comment']) || in_array('*', $fields['comment'])){
			//若未指定返回字段，初始化
			$fields['comment'] = PostComments::model()->getFields();
		}
		
		$comment_fields = $fields['comment'];
		if(!empty($fields['user']) && !in_array('user_id', $comment_fields)){
			//如果要获取作者信息，则必须搜出user_id
			$comment_fields[] = 'user_id';
		}
		if(!empty($fields['parent_comment']) && !in_array('parent', $comment_fields)){
			//如果要获取作者信息，则必须搜出parent
			$comment_fields[] = 'parent';
		}
		
		$comment = PostComments::model()->fetchRow(array(
			'id = ?'=>$comment_id,
			'deleted = 0',
		), $comment_fields);
		
		if(!$comment){
			return false;
		}
		
		$return = array(
			'comment'=>$comment,
		);
		//作者信息
		if(!empty($fields['user'])){
			$return['user'] = User::model()->get($comment['user_id'], implode(',', $fields['user']));
		}
		
		//父节点
		if(!empty($fields['parent_comment'])){
			$parent_comment_fields = $fields['parent_comment'];
			if(!empty($fields['parent_comment_user']) && !in_array('user_id', $parent_comment_fields)){
				//如果要获取作者信息，则必须搜出user_id
				$parent_comment_fields[] = 'user_id';
			}
			
			$parent_comment = PostComments::model()->fetchRow(array(
				'id = ?'=>$comment['parent'],
				'deleted = 0',
			), $parent_comment_fields);
			
			$return['parent_comment'] = $parent_comment;
			
			if($parent_comment){
				if(!empty($fields['parent_comment_user'])){
					$return['parent_comment_user'] = User::model()->get($parent_comment['user_id'], implode(',', $fields['parent_comment_user']));
				}
				if(!in_array('user_id', $fields['parent_comment']) && in_array('user_id', $parent_comment_fields)){
					unset($return['parent_comment']['user_id']);
				}
			}
		}
		
		//过滤掉那些未指定返回，但出于某些原因先搜出来的字段
		foreach(array('user_id', 'parent') as $f){
			if(!in_array($f, $fields['comment']) && in_array($f, $comment_fields)){
				unset($return['comment'][$f]);
			}
		}
		
		return $return;
	}

	/**
	 * 根据当前节点的父ID得到树根节点
	 * @param int $id
	 */
	public function getRootByParnetId($parent_id){
		if($parent_id == 0){return false;}//已经是根节点
		$parent_comment = $this->getParentByParentId($parent_id, 'id,parent');
		if($parent_comment['comment']['parent'] != 0){
			$parent_comment = $this->getRootByParnetId($parent_comment['comment']['parent']);
		}
		return $parent_comment;
	}
	
	/**
	 * 根据当前节点ID得到父节点
	 * @param int $id
	 */
	public function getParent($id){
		$message = PostComments::model()->find($id);
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
	public function getParentByParentId($id, $fields = 'id,user_id,content,parent'){
		if($id == 0){return false;}//根节点无父节点
		return $this->get($id, $fields);
	}
	
	/**
	 * 根据当前节点ID得到树根节点（直接评论文章的评论）
	 * @param int $comment_id
	 */
	public function getRoot($comment_id){
		$parent_message = $this->getParent($comment_id);
		if($parent_message['comment']['parent'] != 0){
			return $this->getRootByParnetId($parent_message['comment']['parent']);
		}else{
			return $parent_message;
		}
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
		
		$root_node = $this->getRootByParnetId($parent);
		if($root_node){
			$root = $root_node['comment']['id'];
		}else{
			$root = 0;
		}
		$comment_id = PostComments::model()->insert(array(
			'post_id'=>$post_id,
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
			$parent_comment = PostComments::model()->find($parent, 'is_terminal');
			if($parent_comment['is_terminal']){
				//标记其父节点为非叶子节点
				PostComments::model()->update(array(
					'is_terminal'=>0,
				), $parent);
			}
		}
		
		//执行钩子
		Hook::getInstance()->call('after_post_comment_created', array(
			'comment_id'=>$comment_id,
		));
		
		return $comment_id;
	}
	
	/**
	 * 软删除一条评论
	 * 软删除不会修改is_terminal和parent标识，因为删除的东西随时都有可能会被恢复，而parent如果变了是无法被恢复的。
	 * @param int $comment_id 评论ID
	 */
	public function delete($comment_id){
		PostComments::model()->update(array(
			'deleted'=>1,
		), $comment_id);
		
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
		
		return PostComments::model()->update(array(
			'deleted'=>1,
		), array(
			'or'=>array(
				'id = ' . $comment['id'],
				'root = ' . $comment['id'],
			)
		));
	}
	
	/**
	 * 永久删除一条评论
	 * @param int $comment_id 评论ID
	 */
	public function remove($comment_id){
		$comment = PostComments::model()->find($comment_id, 'id,parent');
		if(!$comment){
			throw new Exception('指定评论ID不存在');
		}
		
		//执行钩子，这个不能用after，记录都没了就没法找了
		Hook::getInstance()->call('before_post_comment_removed', array(
			'comment_id'=>$comment_id,
		));
		
		if($comment['is_terminal']){
			//是叶子节点，查找其父节点还有没有其他子节点
			if(PostComments::model()->fetchRow(array(
				'parent = ' . $comment['parent'],
			), 'id')){
				//其父节点没有其他子节点，将其父节点标记为叶子节点
				PostComments::model()->update(array(
					'is_terminal'=>1,
				), $comment['parent']);
			}
		}else{
			//不是叶子节点，将其子节点挂到其父节点上
			PostComments::model()->update(array(
				'parent'=>$comment['parent'],
			), array(
				'parent=?'=>$comment_id,
			));
		}
		
		return PostComments::model()->delete($comment_id);
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
		
		return PostComments::model()->delete(array(
			'or'=>array(
				'id = ' . $comment['id'],
				'root = ' . $comment['id'],
			)
		));
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