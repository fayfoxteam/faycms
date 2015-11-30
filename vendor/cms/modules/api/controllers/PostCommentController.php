<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\models\post\Comment;
use fay\core\Response;
use fay\models\tables\Posts;

class PostCommentController extends ApiController{
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
		
	}
}