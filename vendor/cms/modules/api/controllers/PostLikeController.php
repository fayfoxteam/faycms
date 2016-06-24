<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\services\post\Like as LikeService;
use fay\models\post\Like as LikeModel;
use fay\models\Post;
use fay\helpers\FieldHelper;
use fay\models\User;

/**
 * 文章点赞
 */
class PostLikeController extends ApiController{
	/**
	 * 点赞
	 * @parameter int $post_id 文章ID
	 * @parameter string $trackid 追踪ID
	 */
	public function add(){
		//登录检查
		$this->checkLogin();
		
		//表单验证
		$this->form()->setRules(array(
			array(array('post_id'), 'required'),
			array('post_id', 'int', array('min'=>1)),
		))->setFilters(array(
			'post_id'=>'intval',
			'trackid'=>'trim',
		))->setLabels(array(
			'post_id'=>'文章ID',
		))->check();
		
		$post_id = $this->form()->getData('post_id');
		
		if(!Post::isPostIdExist($post_id)){
			Response::notify('error', array(
				'message'=>'文章ID不存在',
				'code'=>'invalid-parameter:post_id-is-not-exist',
			));
		}
		
		if(LikeModel::isLiked($post_id)){
			Response::notify('error', array(
				'message'=>'您已赞过该文章',
				'code'=>'already-favorited',
			));
		}
		
		LikeService::add($post_id, $this->form()->getData('trackid', ''));
		
		Response::notify('success', '点赞成功');
	}
	
	/**
	 * 取消点赞
	 * @parameter int $post_id 文章ID
	 */
	public function remove(){
		//登录检查
		$this->checkLogin();
		
		//表单验证
		$this->form()->setRules(array(
			array(array('post_id'), 'required'),
			array('post_id', 'int', array('min'=>1)),
		))->setFilters(array(
			'post_id'=>'intval',
		))->setLabels(array(
			'post_id'=>'文章ID',
		))->check();
		
		$post_id = $this->form()->getData('post_id');
		
		if(!LikeModel::isLiked($post_id)){
			Response::notify('error', array(
				'message'=>'您未赞过该文章',
				'code'=>'not-liked',
			));
		}
		
		LikeService::remove($post_id);
		
		Response::notify('success', '取消点赞成功');
	}
	
	/**
	 * 文章点赞列表
	 * @parameter int $post_id 文章ID
	 * @parameter string $fields 字段
	 * @parameter int $page 页码
	 * @parameter int $page_size 分页大小
	 */
	public function postLikes(){
		//表单验证
		$this->form()->setRules(array(
			array(array('post_id', 'page', 'page_size'), 'int', array('min'=>1)),
			array('fields', 'fields'),
		))->setFilters(array(
			'post_id'=>'intval',
			'page'=>'intval',
			'page_size'=>'intval',
			'fields'=>'trim',
		))->setLabels(array(
			'page'=>'页码',
			'page_size'=>'分页大小',
		))->check();
		
		$post_id = $this->form()->getData('post_id');
		
		if(!Post::isPostIdExist($post_id)){
			Response::notify('error', array(
				'message'=>'文章ID不存在',
				'code'=>'invalid-parameter:post_id-is-not-exist',
			));
		}
		
		$fields = $this->form()->getData('fields');
		if($fields){
			//过滤字段，移除那些不允许的字段
			$fields = FieldHelper::parse($fields, 'post', User::$public_fields);
		}else{
			$fields = User::$default_fields;
		}
		
		$likes = LikeModel::model()->getPostLikes($post_id,
			$fields,
			$this->form()->getData('page', 1),
			$this->form()->getData('page_size', 20));
		Response::json($likes);
	}
	
	/**
	 * 我的点赞列表（api不支持获取别人的点赞列表）
	 * @parameter string $fields 字段
	 * @parameter int $page 页码
	 * @parameter int $page_size 分页大小
	 */
	public function userLikes(){
		//登录检查
		$this->checkLogin();
		
		//表单验证
		$this->form()->setRules(array(
			array(array('page', 'page_size'), 'int', array('min'=>1)),
			array('fields', 'fields'),
		))->setFilters(array(
			'page'=>'intval',
			'page_size'=>'intval',
			'fields'=>'trim',
		))->setLabels(array(
			'page'=>'页码',
			'page_size'=>'分页大小',
		))->check();
		
		$fields = $this->form()->getData('fields');
		if($fields){
			//过滤字段，移除那些不允许的字段
			$fields = FieldHelper::parse($fields, 'post', Post::$public_fields);
		}else{
			$fields = Post::$default_fields;
		}
		
		$likes = LikeModel::model()->getUserLikes($fields,
			$this->form()->getData('page', 1),
			$this->form()->getData('page_size', 20));
		Response::json($likes);
	}
}