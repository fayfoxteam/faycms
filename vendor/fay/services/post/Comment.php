<?php
namespace fay\services\post;

use fay\core\Model;
use fay\models\tables\PostComments;
use fay\core\Exception;
use fay\core\Hook;
use fay\models\Post;
use fay\models\post\Comment as CommentModel;
use fay\models\Option;
use fay\helpers\ArrayHelper;
use fay\helpers\Request;
use fay\models\tables\PostMeta;

class Comment extends Model{
	/**
	 * @return Comment
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
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
		
		if($parent){
			$parent_comment = PostComments::model()->find($parent, 'post_id,deleted');
			if(!$parent_comment || $parent_comment['deleted']){
				throw new Exception('父节点不存在', 'parent-not-exist');
			}
			if($parent_comment['post_id'] != $post_id){
				throw new Exception('被评论文章ID与指定父节点文章ID不一致', 'post_id-and-parent-not-match');
			}
		}
		
		$comment_id = CommentModel::model()->create(array_merge($extra, array(
			'post_id'=>$post_id,
			'content'=>$content,
			'status'=>$status,
			'user_id'=>$user_id,
			'sockpuppet'=>$sockpuppet,
			'create_time'=>\F::app()->current_time,
			'last_modified_time'=>\F::app()->current_time,
			'ip_int'=>Request::ip2int(\F::app()->ip),
		)), $parent);
		
		//更新文章评论数
		CommentModel::model()->updatePostComments(array(array(
			'post_id'=>$post_id,
			'status'=>$status,
			'sockpuppet'=>$sockpuppet,
		)), 'create');
		
		//执行钩子
		Hook::getInstance()->call('after_post_comment_created', array(
			'comment_id'=>$comment_id,
		));
		
		return $comment_id;
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
		CommentModel::model()->updatePostComments(array($comment), 'delete');
		
		//执行钩子
		Hook::getInstance()->call('after_post_comment_deleted', array(
			'comment_id'=>$comment_id,
		));
	}
	
	/**
	 * 批量删除
	 * @param array $comment_ids 由评论ID构成的一维数组
	 */
	public function batchDelete($comment_ids){
		$comments = PostComments::model()->fetchAll(array(
			'id IN (?)'=>$comment_ids,
			'deleted = 0',
		), 'id,post_id,sockpuppet,status');
		if(!$comments){
			//无符合条件的记录
			return 0;
		}
		
		//更新状态
		$affected_rows = PostComments::model()->update(array(
			'deleted'=>1,
			'last_modified_time'=>\F::app()->current_time,
		), array(
			'id IN (?)'=>$comment_ids,
		));
		
		//更新文章评论数
		CommentModel::model()->updatePostComments($comments, 'delete');
		
		foreach($comments as $c){
			//执行钩子（循环逐条执行）
			Hook::getInstance()->call('after_post_comment_deleted', array(
				'comment_id'=>$c['id'],
			));
		}
		
		return $affected_rows;
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
		CommentModel::model()->updatePostComments(array($comment), 'undelete');
		
		//执行钩子
		Hook::getInstance()->call('after_post_comment_undeleted', array(
			'comment_id'=>$comment_id,
		));
	}
	
	/**
	 * 批量还原
	 * @param array $comment_ids 由评论ID构成的一维数组
	 */
	public function batchUnelete($comment_ids){
		$comments = PostComments::model()->fetchAll(array(
			'id IN (?)'=>$comment_ids,
			'deleted > 0',
		), 'id,post_id,sockpuppet,status');
		if(!$comments){
			//无符合条件的记录
			return 0;
		}
		
		//更新状态
		$affected_rows = PostComments::model()->update(array(
			'deleted'=>0,
			'last_modified_time'=>\F::app()->current_time,
		), array(
			'id IN (?)'=>$comment_ids,
		));
		
		//更新文章评论数
		CommentModel::model()->updatePostComments($comments, 'undelete');
		
		foreach($comments as $c){
			//执行钩子（循环逐条执行）
			Hook::getInstance()->call('after_post_comment_undeleted', array(
				'comment_id'=>$c['id'],
			));
		}
		
		return $affected_rows;
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
			
			//更新文章评论数
			CommentModel::model()->updatePostComments($comments, 'delete');
			
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
		
		CommentModel::model()->remove($comment);
		
		if(!$comment['deleted']){
			//更新文章评论数
			CommentModel::model()->updatePostComments(array($comment), 'remove');
		}
		
		return true;
	}
	
	/**
	 * 物理删除一条评论及所有回复该评论的评论
	 * @param int $comment_id 评论ID
	 */
	public function removeAll($comment_id){
		$comment = PostComments::model()->find($comment_id, '!content');
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
		CommentModel::model()->updatePostComments($undeleted_comments, 'remove');
		
		//执行删除
		CommentModel::model()->removeAll($comment);
		
		return $comment_ids;
	}
	
	/**
	 * 通过审核
	 * @param int $comment_id 评论ID
	 */
	public function approve($comment_id){
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
		
		CommentModel::model()->setStatus($comment_id, PostComments::STATUS_APPROVED);
		
		//更新文章评论数
		CommentModel::model()->updatePostComments(array($comment), 'approve');
		
		//执行钩子
		Hook::getInstance()->call('after_post_comment_approved', array(
			'comment_id'=>$comment_id,
		));
		return true;
	}
	
	/**
	 * 批量通过审核
	 * @param array $comment_ids 由评论ID构成的一维数组
	 */
	public function batchApprove($comment_ids){
		$comments = PostComments::model()->fetchAll(array(
			'id IN (?)'=>$comment_ids,
			'status != ' . PostComments::STATUS_APPROVED,
		), 'id,post_id,sockpuppet,status');
		if(!$comments){
			//无符合条件的记录
			return 0;
		}
		
		//更新状态
		$affected_rows = CommentModel::model()->setStatus(ArrayHelper::column($comments, 'id'), PostComments::STATUS_APPROVED);
		
		//更新文章评论数
		CommentModel::model()->updatePostComments($comments, 'approve');
		
		foreach($comments as $c){
			//执行钩子（循环逐条执行）
			Hook::getInstance()->call('after_post_comment_approved', array(
				'comment_id'=>$c['id'],
			));
		}
		
		return $affected_rows;
	}
	
	/**
	 * 不通过审核
	 * @param int $comment_id 评论ID
	 */
	public function disapprove($comment_id){
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
		
		CommentModel::model()->setStatus($comment_id, PostComments::STATUS_UNAPPROVED);
		
		//更新文章评论数
		CommentModel::model()->updatePostComments(array($comment), 'disapprove');
		
		//执行钩子
		Hook::getInstance()->call('after_post_comment_disapproved', array(
			'comment_id'=>$comment_id,
		));
		return true;
	}
	
	/**
	 * 批量不通过审核
	 * @param array $comment_ids 由评论ID构成的一维数组
	 */
	public function batchDisapprove($comment_ids){
		$comments = PostComments::model()->fetchAll(array(
			'id IN (?)'=>$comment_ids,
			'status != ' . PostComments::STATUS_UNAPPROVED,
		), 'id,post_id,sockpuppet,status');
		if(!$comments){
			//无符合条件的记录
			return 0;
		}
		
		//更新状态
		$affected_rows = CommentModel::model()->setStatus(ArrayHelper::column($comments, 'id'), PostComments::STATUS_UNAPPROVED);
		
		//更新文章评论数
		CommentModel::model()->updatePostComments($comments, 'disapprove');
		
		foreach($comments as $c){
			//执行钩子（循环逐条执行）
			Hook::getInstance()->call('after_post_comment_disapproved', array(
				'comment_id'=>$c['id'],
			));
		}
		
		return $affected_rows;
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
	
	/**
	 * 判断一条动态的改变是否需要改变文章评论数
	 * @param array $comment 单条评论，必须包含status,sockpuppet字段
	 * @param string $action 操作（可选：delete/undelete/remove/create/approve/disapprove）
	 * @param mix $post_comment_verify 是否开启文章评论审核（视为bool）
	 */
	private function needChangePostComments($comment, $action, $post_comment_verify){
		if(in_array($action, array('delete', 'remove', 'undelete', 'create'))){
			if($comment['status'] == PostComments::STATUS_APPROVED || !$post_comment_verify){
				return true;
			}
		}else if($action == 'approve'){
			//只要开启了评论审核，则必然在通过审核的时候文章评论数+1
			if($post_comment_verify){
				return true;
			}
		}else if($action == 'disapprove'){
			//如果评论原本是通过审核状态，且系统开启了文章评论审核，则当评论未通过审核时，相应文章评论数-1
			if($comment['status'] == PostComments::STATUS_APPROVED && $post_comment_verify){
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * 更posts表comments和real_comments字段。
	 * @param array $comments 相关评论（二维数组，每项必须包含post_id,status,sockpuppet字段，且post_id必须都相同）
	 * @param string $action 操作（可选：delete/undelete/remove/create/approve/disapprove）
	 */
	private function updatePostComments($comments, $action){
		$post_comment_verify = Option::get('system:post_comment_verify');
		$posts = array();
		foreach($comments as $c){
			if($this->needChangePostComments($c, $action, $post_comment_verify)){
				//更新评论数
				if(isset($posts[$c['post_id']]['comments'])){
					$posts[$c['post_id']]['comments']++;
				}else{
					$posts[$c['post_id']]['comments'] = 1;
				}
				if(!$c['sockpuppet']){
					//如果不是马甲，更新真实评论数
					if(isset($posts[$c['post_id']]['real_comments'])){
						$posts[$c['post_id']]['real_comments']++;
					}else{
						$posts[$c['post_id']]['real_comments'] = 1;
					}
				}
			}
		}
		
		foreach($posts as $post_id => $comment_count){
			$comments = isset($comment_count['comments']) ? $comment_count['comments'] : 0;
			$real_comments = isset($comment_count['real_comments']) ? $comment_count['real_comments'] : 0;
			if(in_array($action, array('delete', 'remove', 'disapprove'))){
				//如果是删除相关的操作，取反
				$comments = - $comments;
				$real_comments = - $real_comments;
			}
			
			if($comments && $comments == $real_comments){
				//如果全部评论都是真实评论，则一起更新real_comments和comments
				PostMeta::model()->inc($post_id, array('comments', 'real_comments'), $comments);
			}else{
				if($comments){
					PostMeta::model()->inc($post_id, array('comments'), $comments);
				}
				if($real_comments){
					PostMeta::model()->inc($post_id, array('real_comments'), $real_comments);
				}
			}
		}
	}
	
	public function getTree($post_id, $page_size = 10, $page = 1, $fields = 'id,content,parent,create_time,user.id,user.nickname,user.avatar'){
		$conditions = array(
			'deleted = 0',
		);
		if(Option::get('system:post_comment_verify')){
			//开启了评论审核
			$conditions[] = 'status = '.PostComments::STATUS_APPROVED;
		}
		
		return CommentModel::model()->getTree($post_id,
			$page_size,
			$page,
			$fields,
			$conditions
		);
		
	}
}