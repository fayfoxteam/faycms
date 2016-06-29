<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\services\feed\Like as FeedLike;
use fay\models\Feed;
use fay\helpers\FieldHelper;
use fay\models\User;

/**
 * 动态点赞
 */
class FeedLikeController extends ApiController{
	/**
	 * 点赞
	 * @parameter int $feed_id 动态ID
	 * @parameter string $trackid 追踪ID
	 */
	public function add(){
		//登录检查
		$this->checkLogin();
		
		//表单验证
		$this->form()->setRules(array(
			array(array('feed_id'), 'required'),
			array('feed_id', 'int', array('min'=>1)),
		))->setFilters(array(
			'feed_id'=>'intval',
			'trackid'=>'trim',
		))->setLabels(array(
			'feed_id'=>'动态ID',
		))->check();
		
		$feed_id = $this->form()->getData('feed_id');
		
		if(!Feed::isFeedIdExist($feed_id)){
			Response::notify('error', array(
				'message'=>'动态ID不存在',
				'code'=>'invalid-parameter:feed_id-is-not-exist',
			));
		}
		
		if(FeedLike::isLiked($feed_id)){
			Response::notify('error', array(
				'message'=>'您已赞过该动态',
				'code'=>'already-favorited',
			));
		}
		
		FeedLike::add($feed_id, $this->form()->getData('trackid', ''));
		
		Response::notify('success', '点赞成功');
	}
	
	/**
	 * 取消点赞
	 * @parameter int $feed_id 动态ID
	 */
	public function remove(){
		//登录检查
		$this->checkLogin();
		
		//表单验证
		$this->form()->setRules(array(
			array(array('feed_id'), 'required'),
			array('feed_id', 'int', array('min'=>1)),
		))->setFilters(array(
			'feed_id'=>'intval',
		))->setLabels(array(
			'feed_id'=>'动态ID',
		))->check();
		
		$feed_id = $this->form()->getData('feed_id');
		
		if(!FeedLike::isLiked($feed_id)){
			Response::notify('error', array(
				'message'=>'您未赞过该动态',
				'code'=>'not-liked',
			));
		}
		
		FeedLike::remove($feed_id);
		
		Response::notify('success', '取消点赞成功');
	}
	
	/**
	 * 动态点赞列表
	 * @parameter int $feed_id 动态ID
	 * @parameter string $fields 字段
	 * @parameter int $page 页码
	 * @parameter int $page_size 分页大小
	 */
	public function feedLikes(){
		//表单验证
		$this->form()->setRules(array(
			array(array('feed_id', 'page', 'page_size'), 'int', array('min'=>1)),
			array('fields', 'fields'),
		))->setFilters(array(
			'feed_id'=>'intval',
			'page'=>'intval',
			'page_size'=>'intval',
			'fields'=>'trim',
		))->setLabels(array(
			'page'=>'页码',
			'page_size'=>'分页大小',
			'fields'=>'字段',
		))->check();
		
		$feed_id = $this->form()->getData('feed_id');
		
		if(!Feed::isFeedIdExist($feed_id)){
			Response::notify('error', array(
				'message'=>'动态ID不存在',
				'code'=>'invalid-parameter:feed_id-is-not-exist',
			));
		}
		
		$fields = $this->form()->getData('fields');
		if($fields){
			//过滤字段，移除那些不允许的字段
			$fields = FieldHelper::parse($fields, 'feed', User::$public_fields);
		}else{
			$fields = User::$default_fields;
		}
		
		$likes = FeedLike::service()->getFeedLikes($feed_id,
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
			'fields'=>'字段',
		))->check();
		
		$fields = $this->form()->getData('fields');
		if($fields){
			//过滤字段，移除那些不允许的字段
			$fields = FieldHelper::parse($fields, 'feed', Feed::$public_fields);
		}else{
			$fields = Feed::$default_fields;
		}
		
		$likes = FeedLike::service()->getUserLikes($fields,
			$this->form()->getData('page', 1),
			$this->form()->getData('page_size', 20));
		Response::json($likes);
	}
}