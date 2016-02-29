<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\services\post\Comment;
use fay\core\Response;
use fay\models\tables\Posts;
use fay\helpers\FieldHelper;

class PostCommentController extends ApiController{
	/**
	 * 默认返回字段
	 */
	private $default_fields = array(
		'comment'=>array(
			'id', 'content', 'parent', 'create_time',
		),
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
	);
	
	/**
	 * 可选字段
	 */
	private $allowed_fields = array(
		'comment'=>array(
			'id', 'content', 'parent', 'create_time',
		),
		'user'=>array(
			'id', 'username', 'nickname', 'avatar', 'roles'=>array(
				'id', 'title',
			),
		),
	);
	/**
	 * 发表评论
	 * @param int $post_id 文章ID
	 * @param string $content 评论内容
	 * @param int $parent 父评论ID
	 */
	public function create(){
		$this->checkLogin();
		
		if($this->form()->setRules(array(
			array(array('post_id', 'content'), 'required'),
			array(array('post_id'), 'int', array('min'=>1)),
			array(array('parent'), 'int', array('min'=>0)),
			array(array('post_id'), 'exist', array(
				'table'=>'posts',
				'field'=>'id',
				'conditions'=>array(
					'deleted = 0',
					'status = '.Posts::STATUS_PUBLISHED,
					'publish_time < '.\F::app()->current_time,
				)
			)),
			array(array('parent'), 'exist', array(
				'table'=>'post_comments',
				'field'=>'id',
				'conditions'=>array('deleted = 0')
			)),
		))->setFilters(array(
			'post_id'=>'intval',
			'content'=>'trim',
		))->setLabels(array(
			'post_id'=>'文章ID',
			'content'=>'评论内容',
		))->check()){
			$comment_id = Comment::model()->create(
				$this->form()->getData('post_id'),
				$this->form()->getData('content'),
				$this->form()->getData('parent', 0)
			);
			
			$comment = Comment::model()->get($comment_id);
			
			//格式化一下空数组的问题，保证返回给客户端的数据类型一致
			if(isset($comment['parent_comment']) && empty($comment['parent_comment'])){
				$comment['parent_comment'] = new \stdClass();
			}
			if(isset($comment['parent_comment_user']) && empty($comment['parent_comment_user'])){
				$comment['parent_comment_user'] = new \stdClass();
			}
			
			Response::notify('success', array(
				'message'=>'评论成功',
				'data'=>$comment,
			));
		}else{
			$error = $this->form()->getFirstError();
			Response::notify('error', array(
				'message'=>$error['message'],
				'code'=>$error['code'],
			));
		}
	}
	
	/**
	 * 删除评论
	 * @param int $comment_id 评论ID
	 */
	public function delete(){
		$this->checkLogin();
		
		if($this->form()->setRules(array(
			array(array('comment_id'), 'required'),
			array(array('comment_id'), 'int', array('min'=>1)),
			array(array('comment_id'), 'exist', array(
				'table'=>'post_comments',
				'field'=>'id',
				'conditions'=>array('deleted = 0')
			)),
		))->setFilters(array(
			'comment_id'=>'intval',
		))->setLabels(array(
			'comment_id'=>'评论ID',
		))->check()){
			$comment_id = $this->form()->getData('comment_id');
			
			if(Comment::model()->checkPermission($comment_id, 'delete')){
				Comment::model()->delete($comment_id);
				Response::notify('success', '评论删除成功');
			}else{
				Response::notify('error', array(
					'message'=>'您无权操作该评论',
					'code'=>'permission-denied',
				));
			}
		}else{
			$error = $this->form()->getFirstError();
			Response::notify('error', array(
				'message'=>$error['message'],
				'code'=>$error['code'],
			));
		}
	}
	
	/**
	 * 从回收站还原评论
	 * @param int $comment_id 评论ID
	 */
	public function undelete(){
		$this->checkLogin();
		
		if($this->form()->setRules(array(
			array(array('comment_id'), 'required'),
			array(array('comment_id'), 'int', array('min'=>1)),
			array(array('comment_id'), 'exist', array(
				'table'=>'post_comments',
				'field'=>'id',
				'conditions'=>array('deleted != 0')
			)),
		))->setFilters(array(
			'comment_id'=>'intval',
		))->setLabels(array(
			'comment_id'=>'评论ID',
		))->check()){
			$comment_id = $this->form()->getData('comment_id');
			
			if(Comment::model()->checkPermission($comment_id, 'undelete')){
				Comment::model()->undelete($comment_id);
				Response::notify('success', '评论还原成功');
			}else{
				Response::notify('error', array(
					'message'=>'您无权操作该评论',
					'code'=>'permission-denied',
				));
			}
		}else{
			$error = $this->form()->getFirstError();
			Response::notify('error', array(
				'message'=>$error['message'],
				'code'=>$error['code'],
			));
		}
	}
	
	/**
	 * 编辑评论
	 * @param int $comment_id 评论ID
	 * @param string $content 评论内容
	 */
	public function edit(){
		$this->checkLogin();
		if($this->form()->setRules(array(
			array(array('comment_id', 'content'), 'required'),
			array(array('comment_id'), 'int', array('min'=>1)),
			array(array('comment_id'), 'exist', array(
				'table'=>'post_comments',
				'field'=>'id',
				'conditions'=>array('deleted = 0')
			)),
		))->setFilters(array(
			'post_id'=>'intval',
			'content'=>'trim',
		))->setLabels(array(
			'post_id'=>'文章ID',
			'content'=>'评论内容',
		))->check()){
			$comment_id = $this->form()->getData('comment_id');
			
			if(Comment::model()->checkPermission($comment_id, 'edit')){
				Comment::model()->update(
					$comment_id,
					$this->form()->getData('content')
				);
				Response::notify('success', '评论修改成功');
			}else{
				Response::notify('error', array(
					'message'=>'您无权操作该评论',
					'code'=>'permission-denied',
				));
			}
		}else{
			$error = $this->form()->getFirstError();
			Response::notify('error', array(
				'message'=>$error['message'],
				'code'=>$error['code'],
			));
		}
	}
	
	/**
	 * 评论列表
	 */
	public function listAction(){
		if($this->form()->setRules(array(
			array(array('post_id'), 'required'),
			array(array('post_id', 'page', 'page_size'), 'int', array('min'=>1)),
			array(array('post_id'), 'exist', array(
				'table'=>'posts',
				'field'=>'id',
				'conditions'=>array(
					'deleted = 0',
					'status = '.Posts::STATUS_PUBLISHED,
					'publish_time < '.\F::app()->current_time,
				)
			)),
		))->setFilters(array(
			'post_id'=>'intval',
			'page'=>'intval',
			'page_size'=>'intval',
			'mode'=>'trim',
			'fields'=>'trim',
		))->setLabels(array(
			'post_id'=>'文章ID',
			'page'=>'页码',
			'page_size'=>'分页大小',
		))->check()){
			$fields = $this->form()->getData('fields');
			if($fields){
				//过滤字段，移除那些不允许的字段
				$fields = FieldHelper::process($fields, 'comment', $this->allowed_fields);
			}else{
				$fields = $this->default_fields;
			}
			
			switch($this->form()->getData('mode')){
				case 'tree':
					Response::json(array('comments'=>Comment::model()->getTree(
						$this->form()->getData('post_id'),
						$this->form()->getData('page_size', 20),
						$this->form()->getData('page', 1),
						$fields
					)));
					break;
			}
		}else{
			$error = $this->form()->getFirstError();
			Response::notify('error', array(
				'message'=>$error['message'],
				'code'=>$error['code'],
			));
		}
	}
}