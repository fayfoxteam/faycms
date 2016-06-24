<?php
namespace fay\models\post;

use fay\models\MultiTree;
use fay\models\tables\PostComments;
use fay\helpers\FieldHelper;
use fay\models\Option;
use fay\models\tables\PostMeta;
use fay\models\User;
use fay\core\ErrorException;

class Comment extends MultiTree{
	/**
	 * @see MultiTree::$model
	 */
	protected $model = '\fay\models\tables\PostComments';
	
	/**
	 * @see MultiTree::$foreign_key
	 */
	protected $foreign_key = 'post_id';
	
	/**
	 * @see MultiTree::$field_key
	 */
	protected $field_key = 'comment';
	
	/**
	 * @param string $class_name
	 * @return Comment
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取一条评论
	 * @param int $comment_id 评论ID
	 * @param array|string $fields 返回字段
	 *  - comment.*系列可指定post_comments表返回字段，若有一项为'comment.*'，则返回所有字段
	 *  - user.*系列可指定作者信息，格式参照\fay\models\User::get()
	 *  - parent.comment.*系列可指定父评论post_comments表返回字段，若有一项为'comment.*'，则返回所有字段
	 *  - parent._user.*系列可指定父评论作者信息，格式参照\fay\models\User::get()
	 * @return array
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
		$fields = FieldHelper::parse($fields, 'comment');
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
			$return['user'] = User::model()->get(
				$comment['user_id'],
				$fields['user'],
				isset($fields['_extra']['user']) ? $fields['_extra']['user'] : array()
			);
		}
		
		//父节点
		if(!empty($fields['parent'])){
			$parent_comment_fields = isset($fields['parent']['comment']) ? $fields['parent']['comment'] : array();
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
				if(!empty($fields['parent']['user'])){
					$return['parent']['user'] = User::model()->get(
						$parent_comment['user_id'],
						$fields['parent']['user'],
						isset($fields['parent']['_extra']['user']) ? $fields['parent']['_extra']['user'] : array()
					);
				}
				if((!isset($fields['parent']['comment']) || !in_array('user_id', $fields['parent']['comment'])) &&
					in_array('user_id', $parent_comment_fields)){
					unset($parent_comment['user_id']);
				}
				
				if($parent_comment){
					$return['parent']['comment'] = $parent_comment;
				}
			}else{
				//没有父节点，但是要求返回相关父节点字段，则返回空数组
				if(!empty($fields['parent']['comment'])){
					$return['parent']['comment'] = array();
				}
		
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
	 * @param int|array $comment 评论
	 *  - 若是数组，视为评论表行记录，必须包含user_id
	 *  - 若是数字，视为评论ID，会根据ID搜索数据库
	 * @param string $action 操作
	 * @param int|null $user_id 用户ID，若为空，则默认为当前登录用户
	 * @return bool
	 * @throws ErrorException
	 */
	public function checkPermission($comment, $action = 'delete', $user_id = null){
		if(!is_array($comment)){
			$comment = PostComments::model()->find($comment, 'user_id');
		}
		$user_id || $user_id = \F::app()->current_user;
		
		if(empty($comment['user_id'])){
			throw new ErrorException('指定文章评论不存在');
		}
		
		if($comment['user_id'] == $user_id){
			//自己的评论总是有权限操作的
			return true;
		}
		
		if(User::model()->isAdmin($user_id) &&
			User::model()->checkPermission('admin/post-comment/' . $action, $user_id)){
			//是管理员，判断权限
			return true;
		}
		
		return false;
	}
	
	/**
	 * 更新评论状态
	 * @param int|array $comment_id 评论ID或由评论ID构成的一维数组
	 * @param int $status 状态码
	 * @return int
	 */
	public function setStatus($comment_id, $status){
		if(is_array($comment_id)){
			return PostComments::model()->update(array(
				'status'=>$status,
				'last_modified_time'=>\F::app()->current_time,
			), array('id IN (?)'=>$comment_id));
		}else{
			return PostComments::model()->update(array(
				'status'=>$status,
				'last_modified_time'=>\F::app()->current_time,
			), $comment_id);
		}
	}
	
	/**
	 * 判断一条动态的改变是否需要改变文章评论数
	 * @param array $comment 单条评论，必须包含status,sockpuppet字段
	 * @param string $action 操作（可选：delete/undelete/remove/create/approve/disapprove）
	 * @return bool
	 */
	private function needChangePostComments($comment, $action){
		$post_comment_verify = Option::get('system:post_comment_verify');
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
	 * 更post_meta表comments和real_comments字段。
	 * @param array $comments 相关评论（二维数组，每项必须包含post_id,status,sockpuppet字段）
	 * @param string $action 操作（可选：delete/undelete/remove/create/approve/disapprove）
	 */
	public function updatePostComments($comments, $action){
		$posts = array();
		foreach($comments as $c){
			if($this->needChangePostComments($c, $action)){
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
				PostMeta::model()->incr($post_id, array('comments', 'real_comments'), $comments);
			}else{
				if($comments){
					PostMeta::model()->incr($post_id, array('comments'), $comments);
				}
				if($real_comments){
					PostMeta::model()->incr($post_id, array('real_comments'), $real_comments);
				}
			}
		}
	}
	
	/**
	 * 根据文章ID，以树的形式（体现层级结构）返回评论
	 * @param int $post_id 文章ID
	 * @param int $page_size 分页大小
	 * @param int $page 页码
	 * @param array|string $fields 字段
	 * @return array
	 */
	public function getTree($post_id, $page_size = 10, $page = 1, $fields = array(
		'comment'=>array(
			'id', 'content', 'parent', 'create_time',
		),
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
	)){
		$conditions = array(
			'deleted = 0',
		);
		if(Option::get('system:post_comment_verify')){
			//开启了评论审核
			$conditions[] = 'status = '.PostComments::STATUS_APPROVED;
		}
		
		$result = $this->_getTree($post_id,
			$page_size,
			$page,
			$fields,
			$conditions
		);
		
		return array(
			'comments'=>$result['data'],
			'pager'=>$result['pager'],
		);
	}
	
	/**
	 * 根据文章ID，以列表的形式（俗称“盖楼”）返回评论
	 * @param int $post_id 文章ID
	 * @param int $page_size 分页大小
	 * @param int $page 页码
	 * @param array|string $fields 字段
	 * @return array
	 */
	public function getList($post_id, $page_size = 10, $page = 1, $fields = array(
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
			't.deleted = 0',
		);
		$js_conditions = array(
			't2.deleted = 0',
		);
		if(Option::get('system:post_comment_verify')){
			//开启了评论审核
			$conditions[] = 't.status = '.PostComments::STATUS_APPROVED;
			$js_conditions[] = 't2.status = '.PostComments::STATUS_APPROVED;
		}
		
		$result = $this->_getList($post_id,
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
	
	/**
	 * 根据文章ID，以二级树的形式（所有对评论的评论不再体现层级结构）返回评论
	 * @param int $post_id 文章ID
	 * @param int $page_size 分页大小
	 * @param int $page 页码
	 * @param array|string $fields 字段
	 * @return array
	 */
	public function getChats($post_id, $page_size = 10, $page = 1, $fields = array(
		'comment'=>array(
			'id', 'content', 'parent', 'create_time',
		),
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
	)){
		$conditions = array(
			'deleted = 0',
		);
		if(Option::get('system:post_comment_verify')){
			//开启了评论审核
			$conditions[] = 'status = '.PostComments::STATUS_APPROVED;
		}
		
		$result = $this->_getChats($post_id,
			$page_size,
			$page,
			$fields,
			$conditions
		);
		
		return array(
			'comments'=>$result['data'],
			'pager'=>$result['pager'],
		);
	}
}