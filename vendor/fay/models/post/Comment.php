<?php
namespace fay\models\post;

use fay\core\Model;
use fay\models\tables\PostComments;
use fay\core\Exception;
use fay\core\Hook;
use fay\models\Post;
use fay\models\Message;
use fay\helpers\SqlHelper;
use fay\models\Option;
use fay\models\tables\Posts;
use fay\models\MultiTree;
use fay\helpers\ArrayHelper;
use fay\helpers\Request;

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
		$comment = Message::model()->get('\fay\models\tables\PostComments', $comment_id, $this->formatFieldForMessage($fields));
		return $this->formatMessageToComment($comment);
	}
	
	/**
	 * 发表一条文章评论
	 * @param int $post_id 文章ID
	 * @param string $content 评论内容
	 * @param int $parent 父ID，若是回复评论的评论，则带上被评论的评论ID，默认为0
	 * @param int $status 状态（默认为待审核）
	 * @param array $extra 扩展参数，二次开发时可能会用到
	 * @param int $user_id 用户ID，若不指定，默认为当前登录用户ID
	 * @param int $sockpuppet 马甲信息，若是真实用户，传入0，默认为0
	 */
	public function create($post_id, $content, $parent = 0, $status = PostComments::STATUS_PENDING, $extra = array(), $user_id = null, $sockpuppet = 0){
		$user_id === null && $user_id = \F::app()->current_user;
		
		if(!Post::isPostIdExist($post_id)){
			throw new Exception('文章ID不存在', 'post_id-not-exist');
		}
		
		$comment_id = MultiTree::model()->create('\fay\models\tables\PostComments', array_merge($extra, array(
			'content'=>$content,
			'status'=>$status,
			'user_id'=>$user_id,
			'sockpuppet'=>$sockpuppet,
			'create_time'=>\F::app()->current_time,
			'last_modified_time'=>\F::app()->current_time,
			'ip_int'=>Request::ip2int(\F::app()->ip),
		)), $parent);
		
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
		$comment = PostComments::model()->find($comment_id, 'deleted,post_id,status,sockpuppet');
		if(!$comment){
			throw new Exception('指定评论ID不存在', 'comment_id-is-not-exist');
		}
		if($comment['deleted']){
			throw new Exception('评论已删除', 'comment-already-deleted');
		}
		
		//软删除不需要动树结构，只要把deleted字段标记一下即可
		PostComments::model()->update(array(
			'deleted'=>1,
			'last_modified_time'=>\F::app()->current_time,
		), $comment_id);
		
		//更新文章评论数
		$this->updatePostCommentsAndRealCommentsAfterDeleteOrUndelete(array($comment));
		
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
		$comment = PostComments::model()->find($comment_id, 'deleted,post_id,status,sockpuppet');
		if(!$comment){
			throw new Exception('指定评论ID不存在', 'comment_id-is-not-exist');
		}
		if(!$comment['deleted']){
			throw new Exception('指定评论ID不在回收站中', 'comment-not-in-recycle-bin');
		}
		
		//还原不需要动树结构，只是把deleted字段标记一下即可
		PostComments::model()->update(array(
			'deleted'=>0,
			'last_modified_time'=>\F::app()->current_time,
		), $comment_id);
		
		//更新文章评论数
		$this->updatePostCommentsAndRealCommentsAfterDeleteOrUndelete(array($comment), 'undelete');
		
		//执行钩子
		Hook::getInstance()->call('after_post_comment_deleted', array(
			'comment_id'=>$comment_id,
		));
	}
	
	/**
	 * 删除一条评论及所有回复该评论的评论
	 * @param int $comment_id 评论ID
	 */
	public function deleteAll($comment_id){
		$comment = PostComments::model()->find($comment_id, 'left_value,right_value,root');
		if(!$comment){
			throw new Exception('指定评论ID不存在');
		}
		
		//获取所有待删除节点
		$comments = PostComments::model()->fetchAll(array(
			'root = ?'=>$comment['root'],
			'left_value >= ' . $comment['left_value'],
			'right_value <= ' . $comment['right_value'],
			'deleted = 0',
		), 'id,post_id,status,sockpuppet');
		
		if($comments){
			//如果存在待删除节点，则执行删除
			$comment_ids = ArrayHelper::column($comments, 'id');
			PostComments::model()->update(array(
				'deleted'=>1,
				'last_modified_time'=>\F::app()->current_time,
			), array(
				'id IN (?)'=>$comment_ids,
			));
			
			/*
			 * 更新文章评论数
			 * 所有评论对应的post_id必然是同一个，所以先算好增量，然后一次性更新
			 */
			$this->updatePostCommentsAndRealCommentsAfterDeleteOrUndelete($comments);
			
			//执行钩子
			Hook::getInstance()->call('after_post_comment_batch_deleted', array(
				'comment_ids'=>$comment_ids,
			));
			
			return $comment_ids;
		}else{
			return array();
		}
	}
	
	/**
	 * 删除或者还原时，更posts表comments和real_comments字段。
	 * 该函数不会验证原数据的deleted状态，所以传入的$comments必须确保deleted状态。
	 * 像通过审核，拒绝审核这类场景不能用此函数更新。
	 * @param array $comments 相关评论（二维数组，每项必须包含post_id,status,sockpuppet字段）
	 */
	private function updatePostCommentsAndRealCommentsAfterDeleteOrUndelete($comments, $action = 'delete'){
		$post_comment_verify = Option::get('system:post_comment_verify');
		$counts = 0;
		$real_counts = 0;
		foreach($comments as $c){
			//如果评论是通过审核状态或未开启“仅显示通过审核评论”，相关文章评论数-1
			if($c['status'] == PostComments::STATUS_APPROVED || !$post_comment_verify){
				if($c['sockpuppet']){
					$counts++;
				}else{
					$counts++;
					$real_counts++;
				}
			}
		}
		
		if($action == 'delete'){
			$counts = - $counts;
			$real_counts = - $real_counts;
		}
		
		if($counts && $counts == $real_counts){
			//如果全部评论都是真实评论，则一起更新real_comments和comments
			Posts::model()->inc('id = '.$c['post_id'], array('comments', 'real_comments'), $counts);
		}else{
			if($counts){
				Posts::model()->inc('id = '.$c['post_id'], array('comments'), $counts);
			}
			if($real_counts){
				Posts::model()->inc('id = '.$c['post_id'], array('real_comments'), $real_counts);
			}
		}
	}
	
	/**
	 * 永久删除一条评论
	 * @param int $comment_id 评论ID
	 */
	public function remove($comment_id){
		$comment = PostComments::model()->find($comment_id, '!content');
		if(!$comment){
			throw new Exception('指定评论ID不存在');
		}
		
		//执行钩子，这个不能用after，记录都没了就没法找了
		Hook::getInstance()->call('before_post_comment_removed', array(
			'comment_id'=>$comment_id,
		));
		
		MultiTree::model()->remove('\fay\models\tables\PostComments', $comment);
		
		if(!$comment['deleted']){
			//更新文章评论数
			$this->updatePostCommentsAndRealCommentsAfterDeleteOrUndelete(array($comment));
		}
		
		return true;
	}
	
	/**
	 * 物理删除一条评论及所有回复该评论的评论
	 * @param int $comment_id 评论ID
	 */
	public function removeAll($comment_id){
		$comment = PostComments::model()->find($comment_id, 'id,parent');
		if(!$comment){
			throw new Exception('指定评论ID不存在');
		}
		
		//获取所有待删除节点
		$comments = PostComments::model()->fetchAll(array(
			'root = ?'=>$comment['root'],
			'left_value >= ' . $comment['left_value'],
			'right_value <= ' . $comment['right_value'],
		), 'id,post_id,status,sockpuppet');
		$comment_ids = ArrayHelper::column($comments, 'id');
		//执行钩子
		Hook::getInstance()->call('before_post_comment_batch_removed', array(
			'comment_ids'=>$comment_ids,
		));
		
		//获取所有不在回收站内的节点（已删除的显然不需要再更新评论数了）
		$undeleted_comments = array();
		foreach($comment as $c){
			if(!$c['deleted']){
				$undeleted_comments[] = $c;
			}
		}
		//更新文章评论数
		$this->updatePostCommentsAndRealCommentsAfterDeleteOrUndelete($undeleted_comments);
		
		MultiTree::model()->removeAll('\fay\models\tables\PostComments', $comment_id);
		
		return $comment_ids;
	}
	
	/**
	 * 更新评论状态
	 * @param int $comment_id 评论ID
	 * @param int $status 状态码
	 */
	public function setStatus($comment_id, $status){
		return PostComments::model()->update(array(
			'status'=>$status,
			'last_modified_time'=>\F::app()->current_time,
		), $comment_id);
	}
	
	/**
	 * 通过审核
	 * @param int $comment_id 评论ID
	 */
	public function approved($comment_id){
		$comment = PostComments::model()->find($comment_id, '!content');
		if(!$comment){
			throw new Exception('指定评论ID不存在', 'comment_id-is-not-exist');
		}
		if($comment['deleted']){
			throw new Exception('评论已删除', 'comment-deleted');
		}
		if($comment['status'] == PostComments::STATUS_APPROVED){
			throw new Exception('已通过审核，请勿重复操作', 'already-approved');
		}
		
		//更新文章评论数
		if(Option::get('system:post_comment_verify')){
			//如果只显示通过审核的评论，则当评论通过审核时，相应文章评论数+1
			if($comment['sockpuppet']){
				Posts::model()->inc('id = '.$comment['post_id'], array('comments'), 1);
			}else{
				Posts::model()->inc('id = '.$comment['post_id'], array('comments', 'real_comments'), 1);
			}
		}
		$this->setStatus($comment_id, PostComments::STATUS_APPROVED);
		
		//执行钩子
		Hook::getInstance()->call('after_post_comment_approved', array(
			'comment_id'=>$comment_id,
		));
		return true;
	}
	
	/**
	 * 不通过审核
	 * @param int $comment_id 评论ID
	 */
	public function unapproved($comment_id){
		$comment = PostComments::model()->find($comment_id, '!content');
		if(!$comment){
			throw new Exception('指定评论ID不存在', 'comment_id-is-not-exist');
		}
		if($comment['deleted']){
			throw new Exception('评论已删除', 'comment-is-deleted');
		}
		if($comment['status'] == PostComments::STATUS_UNAPPROVED){
			throw new Exception('该评论已是“未通过审核”状态，请勿重复操作', 'already-unapproved');
		}
		
		//更新文章评论数
		if($comment['status'] == PostComments::STATUS_APPROVED && Option::get('system:post_comment_verify')){
			//如果评论原本是通过审核状态，且系统只显示通过审核的评论，则当评论未通过审核时，相应文章评论数-1
			if($comment['sockpuppet']){
				Posts::model()->inc('id = '.$comment['post_id'], array('comments'), -1);
			}else{
				Posts::model()->inc('id = '.$comment['post_id'], array('comments', 'real_comments'), -1);
			}
		}
		$this->setStatus($comment_id, PostComments::STATUS_UNAPPROVED);
		
		//执行钩子
		Hook::getInstance()->call('after_post_comment_unapproved', array(
			'comment_id'=>$comment_id,
		));
		return true;
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