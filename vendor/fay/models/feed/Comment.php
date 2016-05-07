<?php
namespace fay\models\feed;

use fay\models\MultiTree;
use fay\models\tables\FeedComments;
use fay\helpers\FieldHelper;
use fay\models\Option;
use fay\models\tables\PostMeta;
use fay\models\User;
use fay\core\ErrorException;

class Comment extends MultiTree{
	/**
	 * @see MultiTree::$model
	 */
	protected $model = '\fay\models\tables\FeedComments';
	
	/**
	 * @see MultiTree::$foreign_key
	 */
	protected $foreign_key = 'feed_id';
	
	/**
	 * @see MultiTree::$field_key
	 */
	protected $field_key = 'comment';
	
	/**
	 * @return Comment
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取一条评论
	 * @param int $comment_id 评论ID
	 * @param int $fields 返回字段
	 *  - comment.*系列可指定feed_comments表返回字段，若有一项为'comment.*'，则返回所有字段
	 *  - user.*系列可指定作者信息，格式参照\fay\models\User::get()
	 *  - parent_comment.*系列可指定父评论feed_comments表返回字段，若有一项为'comment.*'，则返回所有字段
	 *  - parent_comment_user.*系列可指定父评论作者信息，格式参照\fay\models\User::get()
	 */
	public function get($comment_id, $fields = array(
		'comment'=>array(
			'id', 'content', 'parent', 'create_time',
		),
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
		'parent'=>array(
			'comment'=>array(
				'id', 'content', 'parent', 'create_time',
			),
			'user'=>array(
				'id', 'nickname', 'avatar',
			),
		)
	)){
		$fields = FieldHelper::process($fields, 'comment');
		if(empty($fields['comment']) || in_array('*', $fields['comment'])){
			//若未指定返回字段，初始化
			$fields['comment'] = \F::model($this->model)->getFields(array('status', 'deleted', 'sockpuppet'));
		}
		
		$comment_fields = $fields['comment'];
		if(!empty($fields['user']) && !in_array('user_id', $comment_fields)){
			//如果要获取作者信息，则必须搜出user_id
			$comment_fields[] = 'user_id';
		}
		if(!empty($fields['parent']) && !in_array('parent', $comment_fields)){
			//如果要获取作者信息，则必须搜出parent
			$comment_fields[] = 'parent';
		}
		
		$comment = \F::model($this->model)->fetchRow(array(
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
			$return['user'] = User::model()->get($comment['user_id'], $fields['user']);
		}
		
		//父节点
		if(!empty($fields['parent'])){
			$parent_comment_fields = $fields['parent']['comment'];
			if(!empty($fields['parent']['user']) && !in_array('user_id', $parent_comment_fields)){
				//如果要获取作者信息，则必须搜出user_id
				$parent_comment_fields[] = 'user_id';
			}
		
			$parent_comment = \F::model($this->model)->fetchRow(array(
				'id = ?'=>$comment['parent'],
				'deleted = 0',
			), $parent_comment_fields);
		
			if($parent_comment){
				//有父节点
				$return['parent']['comment'] = $parent_comment;
				if(!empty($fields['parent']['user'])){
					$return['parent']['user'] = User::model()->get($parent_comment['user_id'], $fields['parent']['user']);
				}
				if(!in_array('user_id', $fields['parent']['comment']) && in_array('user_id', $parent_comment_fields)){
					unset($return['parent']['comment']['user_id']);
				}
			}else{
				//没有父节点，但是要求返回相关父节点字段，则返回空数组
				$return['parent']['comment'] = array();
		
				if(!empty($fields['parent']['user'])){
					$return['parent']['user'] = array();
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
	 * 判断用户是否对该评论有删除权限
	 * @param int $comment 评论
	 *  - 若是数组，视为评论表行记录，必须包含user_id
	 *  - 若是数字，视为评论ID，会根据ID搜索数据库
	 * @param string 操作
	 * @param int $user_id 用户ID，若为空，则默认为当前登录用户
	 */
	public function checkPermission($comment, $action = 'delete', $user_id = null){
		if(!is_array($comment)){
			$comment = FeedComments::model()->find($comment, 'user_id');
		}
		$user_id || $user_id = \F::app()->current_user;
		
		if(empty($comment['user_id'])){
			throw new ErrorException('指定动态评论不存在');
		}
		
		if($comment['user_id'] == $user_id){
			//自己的评论总是有权限操作的
			return true;
		}
		
		if(User::model()->isAdmin($user_id) &&
			User::model()->checkPermission('admin/feed-comment/' . $action, $user_id)){
			//是管理员，判断权限
			return true;
		}
		
		return false;
	}
	
	/**
	 * 更新评论状态
	 * @param int|array $comment_id 评论ID或由评论ID构成的一维数组
	 * @param int $status 状态码
	 */
	public function setStatus($comment_id, $status){
		if(is_array($comment_id)){
			return FeedComments::model()->update(array(
				'status'=>$status,
				'last_modified_time'=>\F::app()->current_time,
			), array('id IN (?)'=>$comment_id));
		}else{
			return FeedComments::model()->update(array(
				'status'=>$status,
				'last_modified_time'=>\F::app()->current_time,
			), $comment_id);
		}
	}
	
	/**
	 * 判断一条动态的改变是否需要改变动态评论数
	 * @param array $comment 单条评论，必须包含status,sockpuppet字段
	 * @param string $action 操作（可选：delete/undelete/remove/create/approve/disapprove）
	 * @param mix $feed_comment_verify 是否开启动态评论审核（视为bool）
	 */
	private function needChangeFeedComments($comment, $action, $feed_comment_verify){
		if(in_array($action, array('delete', 'remove', 'undelete', 'create'))){
			if($comment['status'] == FeedComments::STATUS_APPROVED || !$feed_comment_verify){
				return true;
			}
		}else if($action == 'approve'){
			//只要开启了评论审核，则必然在通过审核的时候动态评论数+1
			if($feed_comment_verify){
				return true;
			}
		}else if($action == 'disapprove'){
			//如果评论原本是通过审核状态，且系统开启了动态评论审核，则当评论未通过审核时，相应动态评论数-1
			if($comment['status'] == FeedComments::STATUS_APPROVED && $feed_comment_verify){
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * 更feeds表comments和real_comments字段。
	 * @param array $comments 相关评论（二维数组，每项必须包含feed_id,status,sockpuppet字段，且feed_id必须都相同）
	 * @param string $action 操作（可选：delete/undelete/remove/create/approve/disapprove）
	 */
	public function updateFeedComments($comments, $action){
		$feed_comment_verify = Option::get('system:feed_comment_verify');
		$feeds = array();
		foreach($comments as $c){
			if($this->needChangeFeedComments($c, $action, $feed_comment_verify)){
				//更新评论数
				if(isset($feeds[$c['feed_id']]['comments'])){
					$feeds[$c['feed_id']]['comments']++;
				}else{
					$feeds[$c['feed_id']]['comments'] = 1;
				}
				if(!$c['sockpuppet']){
					//如果不是马甲，更新真实评论数
					if(isset($feeds[$c['feed_id']]['real_comments'])){
						$feeds[$c['feed_id']]['real_comments']++;
					}else{
						$feeds[$c['feed_id']]['real_comments'] = 1;
					}
				}
			}
		}
		
		foreach($feeds as $feed_id => $comment_count){
			$comments = isset($comment_count['comments']) ? $comment_count['comments'] : 0;
			$real_comments = isset($comment_count['real_comments']) ? $comment_count['real_comments'] : 0;
			if(in_array($action, array('delete', 'remove', 'disapprove'))){
				//如果是删除相关的操作，取反
				$comments = - $comments;
				$real_comments = - $real_comments;
			}
			
			if($comments && $comments == $real_comments){
				//如果全部评论都是真实评论，则一起更新real_comments和comments
				PostMeta::model()->incr($feed_id, array('comments', 'real_comments'), $comments);
			}else{
				if($comments){
					PostMeta::model()->incr($feed_id, array('comments'), $comments);
				}
				if($real_comments){
					PostMeta::model()->incr($feed_id, array('real_comments'), $real_comments);
				}
			}
		}
	}
	
	/**
	 * 根据动态ID，以树的形式（体现层级结构）返回评论
	 * @param int $feed_id 动态ID
	 * @param int $page_size 分页大小
	 * @param int $page 页码
	 * @param string $fields 字段
	 */
	public function getTree($feed_id, $page_size = 10, $page = 1, $fields = 'id,content,parent,create_time,user.id,user.nickname,user.avatar'){
		$conditions = array(
			'deleted = 0',
		);
		if(Option::get('system:feed_comment_verify')){
			//开启了评论审核
			$conditions[] = 'status = '.FeedComments::STATUS_APPROVED;
		}
		
		return $this->_getTree($feed_id,
			$page_size,
			$page,
			$fields,
			$conditions
		);
	}
	
	/**
	 * 根据动态ID，以列表的形式（俗称“盖楼”）返回评论
	 * @param int $feed_id 动态ID
	 * @param int $page_size 分页大小
	 * @param int $page 页码
	 * @param string $fields 字段
	 */
	public function getList($feed_id, $page_size = 10, $page = 1, $fields = array(
		'comment'=>array(
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
			'c.deleted = 0',
		);
		$js_conditions = array(
			'c2.deleted = 0',
		);
		if(Option::get('system:post_comment_verify')){
			//开启了评论审核
			$conditions[] = 'c.status = '.FeedComments::STATUS_APPROVED;
			$js_conditions[] = 'c2.status = '.FeedComments::STATUS_APPROVED;
		}
		
		$result = $this->_getList($feed_id,
			$page_size,
			$page,
			$fields,
			$conditions,
			$js_conditions
		);
		
		return array(
			'comments'=>$result['data'],
			'pager'=>$result['pager'],
		);
	}
}