<?php
namespace fay\services\feed;

use fay\core\ErrorException;
use fay\core\Loader;
use fay\helpers\FieldHelper;
use fay\models\MultiTree;
use fay\models\tables\FeedComments;
use fay\core\Exception;
use fay\core\Hook;
use fay\helpers\ArrayHelper;
use fay\helpers\Request;
use fay\models\Feed;
use fay\models\tables\FeedMeta;
use fay\services\Option;
use fay\services\User;

class Comment extends MultiTree{
	/**
	 * @see MultiTree::$model
	 */
	protected $model = 'fay\models\tables\FeedComments';
	
	/**
	 * @see MultiTree::$foreign_key
	 */
	protected $foreign_key = 'feed_id';
	
	/**
	 * @see MultiTree::$field_key
	 */
	protected $field_key = 'comment';
	
	/**
	 * @param string $class_name
	 * @return Comment
	 */
	public static function service($class_name = __CLASS__){
		return Loader::singleton($class_name);
	}
	
	/**
	 * 发表一条文章评论
	 * @param int $feed_id 文章ID
	 * @param string $content 评论内容
	 * @param int $parent 父ID，若是回复评论的评论，则带上被评论的评论ID，默认为0
	 * @param int $status 状态（默认为待审核）
	 * @param array $extra 扩展参数，二次开发时可能会用到
	 * @param int $user_id 用户ID，若不指定，默认为当前登录用户ID
	 * @param int $sockpuppet 马甲信息，若是真实用户，传入0，默认为0
	 * @return int
	 * @throws Exception
	 */
	public function create($feed_id, $content, $parent = 0, $status = FeedComments::STATUS_PENDING, $extra = array(), $user_id = null, $sockpuppet = 0){
		$user_id === null && $user_id = \F::app()->current_user;
		
		if(!Feed::isFeedIdExist($feed_id)){
			throw new Exception('文章ID不存在', 'feed_id-not-exist');
		}
		
		if($parent){
			$parent_comment = FeedComments::model()->find($parent, 'feed_id,deleted');
			if(!$parent_comment || $parent_comment['deleted']){
				throw new Exception('父节点不存在', 'parent-not-exist');
			}
			if($parent_comment['feed_id'] != $feed_id){
				throw new Exception('被评论文章ID与指定父节点文章ID不一致', 'feed_id-and-parent-not-match');
			}
		}
		
		$comment_id = $this->_create(array_merge($extra, array(
			'feed_id'=>$feed_id,
			'content'=>$content,
			'status'=>$status,
			'user_id'=>$user_id,
			'sockpuppet'=>$sockpuppet,
			'create_time'=>\F::app()->current_time,
			'last_modified_time'=>\F::app()->current_time,
			'ip_int'=>Request::ip2int(\F::app()->ip),
		)), $parent);
		
		//更新文章评论数
		$this->updateFeedComments(array(array(
			'feed_id'=>$feed_id,
			'status'=>$status,
			'sockpuppet'=>$sockpuppet,
		)), 'create');
		
		//执行钩子
		Hook::getInstance()->call('after_feed_comment_created', array(
			'comment_id'=>$comment_id,
		));
		
		return $comment_id;
	}
	
	/**
	 * 软删除一条评论
	 * 软删除不会修改parent标识，因为删除的东西随时都有可能会被恢复，而parent如果变了是无法被恢复的。
	 * @param int $comment_id 评论ID
	 * @throws Exception
	 */
	public function delete($comment_id){
		$comment = FeedComments::model()->find($comment_id, 'deleted,feed_id,status,sockpuppet');
		if(!$comment){
			throw new Exception('指定评论ID不存在', 'comment_id-is-not-exist');
		}
		if($comment['deleted']){
			throw new Exception('评论已删除', 'comment-already-deleted');
		}
		
		//软删除不需要动树结构，只要把deleted字段标记一下即可
		FeedComments::model()->update(array(
			'deleted'=>1,
			'last_modified_time'=>\F::app()->current_time,
		), $comment_id);
		
		//更新文章评论数
		$this->updateFeedComments(array($comment), 'delete');
		
		//执行钩子
		Hook::getInstance()->call('after_feed_comment_deleted', array(
			'comment_id'=>$comment_id,
		));
	}
	
	/**
	 * 批量删除
	 * @param array $comment_ids 由评论ID构成的一维数组
	 * @return int
	 */
	public function batchDelete($comment_ids){
		$comments = FeedComments::model()->fetchAll(array(
			'id IN (?)'=>$comment_ids,
			'deleted = 0',
		), 'id,feed_id,sockpuppet,status');
		if(!$comments){
			//无符合条件的记录
			return 0;
		}
		
		//更新状态
		$affected_rows = FeedComments::model()->update(array(
			'deleted'=>1,
			'last_modified_time'=>\F::app()->current_time,
		), array(
			'id IN (?)'=>$comment_ids,
		));
		
		//更新文章评论数
		$this->updateFeedComments($comments, 'delete');
		
		foreach($comments as $c){
			//执行钩子（循环逐条执行）
			Hook::getInstance()->call('after_feed_comment_deleted', array(
				'comment_id'=>$c['id'],
			));
		}
		
		return $affected_rows;
	}
	
	/**
	 * 从回收站恢复一条评论
	 * @param int $comment_id 评论ID
	 * @throws Exception
	 */
	public function undelete($comment_id){
		$comment = FeedComments::model()->find($comment_id, 'deleted,feed_id,status,sockpuppet');
		if(!$comment){
			throw new Exception('指定评论ID不存在', 'comment_id-is-not-exist');
		}
		if(!$comment['deleted']){
			throw new Exception('指定评论ID不在回收站中', 'comment-not-in-recycle-bin');
		}
		
		//还原不需要动树结构，只是把deleted字段标记一下即可
		FeedComments::model()->update(array(
			'deleted'=>0,
			'last_modified_time'=>\F::app()->current_time,
		), $comment_id);
		
		//更新文章评论数
		$this->updateFeedComments(array($comment), 'undelete');
		
		//执行钩子
		Hook::getInstance()->call('after_feed_comment_undeleted', array(
			'comment_id'=>$comment_id,
		));
	}
	
	/**
	 * 批量还原
	 * @param array $comment_ids 由评论ID构成的一维数组
	 * @return int
	 */
	public function batchUnelete($comment_ids){
		$comments = FeedComments::model()->fetchAll(array(
			'id IN (?)'=>$comment_ids,
			'deleted > 0',
		), 'id,feed_id,sockpuppet,status');
		if(!$comments){
			//无符合条件的记录
			return 0;
		}
		
		//更新状态
		$affected_rows = FeedComments::model()->update(array(
			'deleted'=>0,
			'last_modified_time'=>\F::app()->current_time,
		), array(
			'id IN (?)'=>$comment_ids,
		));
		
		//更新文章评论数
		$this->updateFeedComments($comments, 'undelete');
		
		foreach($comments as $c){
			//执行钩子（循环逐条执行）
			Hook::getInstance()->call('after_feed_comment_undeleted', array(
				'comment_id'=>$c['id'],
			));
		}
		
		return $affected_rows;
	}
	
	/**
	 * 删除一条评论及所有回复该评论的评论
	 * @param int $comment_id 评论ID
	 * @return array
	 * @throws Exception
	 */
	public function deleteAll($comment_id){
		$comment = FeedComments::model()->find($comment_id, 'left_value,right_value,root');
		if(!$comment){
			throw new Exception('指定评论ID不存在');
		}
		
		//获取所有待删除节点
		$comments = FeedComments::model()->fetchAll(array(
			'root = ?'=>$comment['root'],
			'left_value >= ' . $comment['left_value'],
			'right_value <= ' . $comment['right_value'],
			'deleted = 0',
		), 'id,feed_id,status,sockpuppet');
		
		if($comments){
			//如果存在待删除节点，则执行删除
			$comment_ids = ArrayHelper::column($comments, 'id');
			FeedComments::model()->update(array(
				'deleted'=>1,
				'last_modified_time'=>\F::app()->current_time,
			), array(
				'id IN (?)'=>$comment_ids,
			));
			
			//更新文章评论数
			$this->updateFeedComments($comments, 'delete');
			
			//执行钩子
			Hook::getInstance()->call('after_feed_comment_batch_deleted', array(
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
	 * @return bool
	 * @throws Exception
	 */
	public function remove($comment_id){
		$comment = FeedComments::model()->find($comment_id, '!content');
		if(!$comment){
			throw new Exception('指定评论ID不存在');
		}
		
		//执行钩子，这个不能用after，记录都没了就没法找了
		Hook::getInstance()->call('before_feed_comment_removed', array(
			'comment_id'=>$comment_id,
		));
		
		$this->_remove($comment);
		
		if(!$comment['deleted']){
			//更新文章评论数
			$this->updateFeedComments(array($comment), 'remove');
		}
		
		return true;
	}
	
	/**
	 * 物理删除一条评论及所有回复该评论的评论
	 * @param int $comment_id 评论ID
	 * @return array
	 * @throws Exception
	 */
	public function removeAll($comment_id){
		$comment = FeedComments::model()->find($comment_id, '!content');
		if(!$comment){
			throw new Exception('指定评论ID不存在');
		}
		
		//获取所有待删除节点
		$comments = FeedComments::model()->fetchAll(array(
			'root = ?'=>$comment['root'],
			'left_value >= ' . $comment['left_value'],
			'right_value <= ' . $comment['right_value'],
		), 'id,feed_id,status,sockpuppet');
		$comment_ids = ArrayHelper::column($comments, 'id');
		//执行钩子
		Hook::getInstance()->call('before_feed_comment_batch_removed', array(
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
		$this->updateFeedComments($undeleted_comments, 'remove');
		
		//执行删除
		$this->_removeAll($comment);
		
		return $comment_ids;
	}
	
	/**
	 * 通过审核
	 * @param int $comment_id 评论ID
	 * @return bool
	 * @throws Exception
	 */
	public function approve($comment_id){
		$comment = FeedComments::model()->find($comment_id, '!content');
		if(!$comment){
			throw new Exception('指定评论ID不存在', 'comment_id-is-not-exist');
		}
		if($comment['deleted']){
			throw new Exception('评论已删除', 'comment-deleted');
		}
		if($comment['status'] == FeedComments::STATUS_APPROVED){
			throw new Exception('已通过审核，请勿重复操作', 'already-approved');
		}
		
		$this->setStatus($comment_id, FeedComments::STATUS_APPROVED);
		
		//更新文章评论数
		$this->updateFeedComments(array($comment), 'approve');
		
		//执行钩子
		Hook::getInstance()->call('after_feed_comment_approved', array(
			'comment_id'=>$comment_id,
		));
		return true;
	}
	
	/**
	 * 批量通过审核
	 * @param array $comment_ids 由评论ID构成的一维数组
	 * @return int
	 */
	public function batchApprove($comment_ids){
		$comments = FeedComments::model()->fetchAll(array(
			'id IN (?)'=>$comment_ids,
			'status != ' . FeedComments::STATUS_APPROVED,
		), 'id,feed_id,sockpuppet,status');
		if(!$comments){
			//无符合条件的记录
			return 0;
		}
		
		//更新状态
		$affected_rows = $this->setStatus(ArrayHelper::column($comments, 'id'), FeedComments::STATUS_APPROVED);
		
		//更新文章评论数
		$this->updateFeedComments($comments, 'approve');
		
		foreach($comments as $c){
			//执行钩子（循环逐条执行）
			Hook::getInstance()->call('after_feed_comment_approved', array(
				'comment_id'=>$c['id'],
			));
		}
		
		return $affected_rows;
	}
	
	/**
	 * 不通过审核
	 * @param int $comment_id 评论ID
	 * @return bool
	 * @throws Exception
	 */
	public function disapprove($comment_id){
		$comment = FeedComments::model()->find($comment_id, '!content');
		if(!$comment){
			throw new Exception('指定评论ID不存在', 'comment_id-is-not-exist');
		}
		if($comment['deleted']){
			throw new Exception('评论已删除', 'comment-is-deleted');
		}
		if($comment['status'] == FeedComments::STATUS_UNAPPROVED){
			throw new Exception('该评论已是“未通过审核”状态，请勿重复操作', 'already-unapproved');
		}
		
		$this->setStatus($comment_id, FeedComments::STATUS_UNAPPROVED);
		
		//更新文章评论数
		$this->updateFeedComments(array($comment), 'disapprove');
		
		//执行钩子
		Hook::getInstance()->call('after_feed_comment_disapproved', array(
			'comment_id'=>$comment_id,
		));
		return true;
	}
	
	/**
	 * 批量不通过审核
	 * @param array $comment_ids 由评论ID构成的一维数组
	 * @return int
	 */
	public function batchDisapprove($comment_ids){
		$comments = FeedComments::model()->fetchAll(array(
			'id IN (?)'=>$comment_ids,
			'status != ' . FeedComments::STATUS_UNAPPROVED,
		), 'id,feed_id,sockpuppet,status');
		if(!$comments){
			//无符合条件的记录
			return 0;
		}
		
		//更新状态
		$affected_rows = $this->setStatus(ArrayHelper::column($comments, 'id'), FeedComments::STATUS_UNAPPROVED);
		
		//更新文章评论数
		$this->updateFeedComments($comments, 'disapprove');
		
		foreach($comments as $c){
			//执行钩子（循环逐条执行）
			Hook::getInstance()->call('after_feed_comment_disapproved', array(
				'comment_id'=>$c['id'],
			));
		}
		
		return $affected_rows;
	}
	
	/**
	 * 编辑一条评论（只能编辑评论内容部分）
	 * @param int $comment_id 评论ID
	 * @param string $content 评论内容
	 * @return int
	 */
	public function update($comment_id, $content){
		return FeedComments::model()->update(array(
			'content'=>$content,
		), $comment_id);
	}
	
	/**
	 * 获取一条评论
	 * @param int $comment_id 评论ID
	 * @param string|array $fields 返回字段
	 *  - comment.*系列可指定feed_comments表返回字段，若有一项为'comment.*'，则返回所有字段
	 *  - user.*系列可指定作者信息，格式参照\fay\services\User::get()
	 *  - parent.comment.*系列可指定父评论feed_comments表返回字段，若有一项为'comment.*'，则返回所有字段
	 *  - parent.user.*系列可指定父评论作者信息，格式参照\fay\services\User::get()
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
			$fields['comment'] = \F::table($this->model)->getFields(array('status', 'deleted', 'sockpuppet'));
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
		
		$comment = \F::table($this->model)->fetchRow(array(
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
			$return['user'] = User::service()->get($comment['user_id'], $fields['user']);
		}
		
		//父节点
		if(!empty($fields['parent'])){
			$parent_comment_fields = $fields['parent']['comment'];
			if(!empty($fields['parent']['user']) && !in_array('user_id', $parent_comment_fields)){
				//如果要获取作者信息，则必须搜出user_id
				$parent_comment_fields[] = 'user_id';
			}
			
			$parent_comment = \F::table($this->model)->fetchRow(array(
				'id = ?'=>$comment['parent'],
				'deleted = 0',
			), $parent_comment_fields);
			
			if($parent_comment){
				//有父节点
				$return['parent']['comment'] = $parent_comment;
				if(!empty($fields['parent']['user'])){
					$return['parent']['user'] = User::service()->get($parent_comment['user_id'], $fields['parent']['user']);
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
	 * @param string $action 操作
	 * @param int $user_id 用户ID，若为空，则默认为当前登录用户
	 * @return bool
	 * @throws ErrorException
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
		
		if(User::service()->isAdmin($user_id) &&
			User::service()->checkPermission('admin/feed-comment/' . $action, $user_id)){
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
	 * @return bool
	 */
	private function needChangeFeedComments($comment, $action){
		$feed_comment_verify = Option::get('system:feed_comment_verify');
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
	 * 更feed_meta表comments和real_comments字段。
	 * @param array $comments 相关评论（二维数组，每项必须包含feed_id,status,sockpuppet字段）
	 * @param string $action 操作（可选：delete/undelete/remove/create/approve/disapprove）
	 */
	public function updateFeedComments($comments, $action){
		$feeds = array();
		foreach($comments as $c){
			if($this->needChangeFeedComments($c, $action)){
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
				FeedMeta::model()->incr($feed_id, array('comments', 'real_comments'), $comments);
			}else{
				if($comments){
					FeedMeta::model()->incr($feed_id, array('comments'), $comments);
				}
				if($real_comments){
					FeedMeta::model()->incr($feed_id, array('real_comments'), $real_comments);
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
	 * @return array
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
	 * @param array|string $fields 字段
	 * @return array
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