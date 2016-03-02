<?php
namespace fay\models\post;

use fay\models\MultiTree;
use fay\models\tables\PostComments;
use fay\helpers\FieldHelper;
use fay\models\Option;
use fay\models\tables\PostMeta;
use fay\models\User;
use fay\core\Sql;
use fay\common\ListView;
use fay\helpers\ArrayHelper;

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
	 * @return Comment
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
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
	 * 更新评论状态
	 * @param int|array $comment_id 评论ID或由评论ID构成的一维数组
	 * @param int $status 状态码
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
	public function updatePostComments($comments, $action){
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
	
	/**
	 * 根据文章ID，以树的形式（体现层级结构）返回评论
	 * @param int $post_id 文章ID
	 * @param int $page_size 分页大小
	 * @param int $page 页码
	 * @param string $fields 字段
	 */
	public function getTree($post_id, $page_size = 10, $page = 1, $fields = 'id,content,parent,create_time,user.id,user.nickname,user.avatar'){
		$conditions = array(
			'deleted = 0',
		);
		if(Option::get('system:post_comment_verify')){
			//开启了评论审核
			$conditions[] = 'status = '.PostComments::STATUS_APPROVED;
		}
		
		return $this->_getTree($post_id,
			$page_size,
			$page,
			$fields,
			$conditions
		);
	}
	
	/**
	 * 根据文章ID，以列表的形式（俗称“盖楼”）返回评论
	 * @param int $post_id 文章ID
	 * @param int $page_size 分页大小
	 * @param int $page 页码
	 * @param string $fields 字段
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
		//解析$fields
		$fields = FieldHelper::process($fields, $this->field_key);
		if(empty($fields[$this->field_key]) || in_array('*', $fields[$this->field_key])){
			$fields[$this->field_key] = PostComments::model()->getFields();
		}
		
		$comment_fields = $fields[$this->field_key];
		if(!empty($fields['user']) && !in_array('user_id', $comment_fields)){
			//若需要获取用户信息，但评论字段未指定user_id，则插入user_id
			$comment_fields[] = 'user_id';
		}
		
		if(!empty($fields['parent'])){
			if(!empty($fields['parent'][$this->field_key]) && in_array('*', $fields['parent'][$this->field_key])){
				$fields['parent'][$this->field_key] = PostComments::model()->getFields();
			}
			$parent_comment_fields = empty($fields['parent'][$this->field_key]) ? array() : $fields['parent'][$this->field_key];
			if(!empty($fields['parent']['user']) && !in_array('user_id', $parent_comment_fields)){
				//若需要获取父节点用户信息，但父节点评论字段未指定user_id，则插入user_id
				$parent_comment_fields[] = 'user_id';
			}
		}
		
		$sql = new Sql();
		$sql->from('post_comments', 'pc', $comment_fields)
			->where('pc.deleted = 0')
			->order('pc.id DESC')
		;
		
		$post_comment_verify = Option::get('system:post_comment_verify');
		if($post_comment_verify){
			$sql->where('pc.status = ' . PostComments::STATUS_APPROVED);
		}
		
		if($parent_comment_fields){
			//表自连接，字段名都是一样的，需要设置别名
			foreach($parent_comment_fields as $key => $f){
				$parent_comment_fields[$key] = $f . ' AS parent_' . $f;
			}
			if($post_comment_verify){
				//开启审核，仅返回通过审核的评论
				$sql->joinLeft('post_comments', 'pc2', 'pc.parent = pc2.id AND pc2.Deleted = 0 AND pc2.status = ' . PostComments::STATUS_APPROVED, $parent_comment_fields);
			}else{
				//未开启审核，返回全部未删除评论
				$sql->joinLeft('post_comments', 'pc2', 'pc.parent = pc2.id AND pc2.Deleted = 0', $parent_comment_fields);
			}
		}
		
		$listview = new ListView($sql, array(
			'current_page'=>$page,
			'page_size'=>$page_size,
		));
		
		$data = $listview->getData();
		
		if(!empty($fields['user'])){
			//获取评论用户信息集合
			$users = User::model()->mget(ArrayHelper::column($data, 'user_id'), $fields['user']);
		}
		if(!empty($fields['parent']['user'])){
			//获取父节点评论用户信息集合
			$parent_users = User::model()->mget(ArrayHelper::column($data, 'parent_user_id'), $fields['parent']['user']);
		}
		$comments = array();
		
		foreach($data as $k => $d){
			$comment = array();
			//评论字段
			foreach($fields['comment'] as $cf){
				$comment['comment'][$cf] = $d[$cf];
			}
			
			//作者字段
			if(!empty($fields['user'])){
				$comment['user'] = $users[$d['user_id']];
			}
			
			//父评论字段
			if(!empty($fields['parent']['comment'])){
				if($d['parent_' . $fields['parent']['comment'][0]] === null){
					//为null的话意味着父节点不存在或已删除（数据库字段一律非null）
					$comment['parent']['comment'] = array();
				}else{
					foreach($fields['parent']['comment'] as $pcf){
						$comment['parent']['comment'][$pcf] = $d['parent_' . $pcf];
					}
				}
			}
			
			//父评论作者字段
			if(!empty($fields['parent']['user'])){
				$comment['parent']['user'] = $parent_users[$d['parent_user_id']];
			}
			
			$comments[] = $comment;
		}
		
		return array(
			'comments'=>$comments,
			'pager'=>$listview->getPager(),
		);
	}
}