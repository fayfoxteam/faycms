<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\services\feed\Like as LikeService;
use fay\models\feed\Like as LikeModel;
use fay\models\Feed;
use fay\helpers\FieldHelper;
use fay\models\User;

/**
 * 动态点赞
 */
class FeedLikeController extends ApiController{
	/**
	 * 点赞
	 * @param int $feed_id 动态ID
	 * @param string $trackid 追踪ID
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
		
		if(LikeModel::isLiked($feed_id)){
			Response::notify('error', array(
				'message'=>'您已赞过该动态',
				'code'=>'already-favorited',
			));
		}
		
		LikeService::add($feed_id, $this->form()->getData('trackid', ''));
		
		Response::notify('success', '点赞成功');
	}
	
	/**
	 * 取消点赞
	 * @param int $feed_id 动态ID
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
		
		if(!LikeModel::isLiked($feed_id)){
			Response::notify('error', array(
				'message'=>'您未赞过该动态',
				'code'=>'not-liked',
			));
		}
		
		LikeService::remove($feed_id);
		
		Response::notify('success', '取消点赞成功');
	}
	
	/**
	 * 动态点赞列表
	 * @param int $feed_id 动态ID
	 * @param string $fields 字段
	 * @param int $page 页码
	 * @param int $page_size 分页大小
	 */
	public function feedLikes(){
		//表单验证
		$this->form()->setRules(array(
			array(array('feed_id', 'page', 'page_size'), 'int', array('min'=>1)),
		))->setFilters(array(
			'feed_id'=>'intval',
			'page'=>'intval',
			'page_size'=>'intval',
			'fields'=>'trim',
		))->setLabels(array(
			'page'=>'页码',
			'page_size'=>'分页大小',
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
			$fields = FieldHelper::process($fields, 'feed', User::$public_fields);
		}else{
			$fields = User::$default_fields;
		}
		
		$likes = LikeModel::model()->getFeedLikes($feed_id,
			$fields,
			$this->form()->getData('page', 1),
			$this->form()->getData('page_size', 20));
		Response::json($likes);
	}
	
	/**
	 * 我的点赞列表（api不支持获取别人的点赞列表）
	 * @param string $fields 字段
	 * @param int $page 页码
	 * @param int $page_size 分页大小
	 */
	public function userLikes(){
		//登录检查
		$this->checkLogin();
		
		//表单验证
		$this->form()->setRules(array(
			array(array('page', 'page_size'), 'int', array('min'=>1)),
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
			$fields = FieldHelper::process($fields, 'feed', Feed::$public_fields);
		}else{
			$fields = Feed::$default_fields;
		}
		
		$likes = LikeModel::model()->getUserLikes($fields,
			$this->form()->getData('page', 1),
			$this->form()->getData('page_size', 20));
		Response::json($likes);
	}
}