<?php
namespace cms\modules\api\controllers;

use cms\library\UserController;
use fay\services\feed\Favorite as FavoriteService;
use fay\models\feed\Favorite as FavoriteModel;
use fay\core\Response;
use fay\models\Feed;
use fay\helpers\FieldHelper;

/**
 * 动态收藏
 */
class FeedFavoriteController extends UserController{
	/**
	 * 收藏
	 * @parameter int $feed_id 动态ID
	 * @parameter string $trackid 追踪ID
	 */
	public function add(){
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
		
		if(FavoriteModel::isFavorited($feed_id)){
			Response::notify('error', array(
				'message'=>'您已收藏过该动态',
				'code'=>'already-favorited',
			));
		}
		
		FavoriteService::add($feed_id, $this->form()->getData('trackid', ''));
		
		Response::notify('success', '收藏成功');
	}
	
	/**
	 * 取消收藏
	 * @parameter int $feed_id 动态ID
	 */
	public function remove(){
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
		
		if(!FavoriteModel::isFavorited($feed_id)){
			Response::notify('error', array(
				'message'=>'您未收藏过该动态',
				'code'=>'not-favorited',
			));
		}
		
		FavoriteService::remove($feed_id);
		
		Response::notify('success', '移除收藏成功');
	}
	
	/**
	 * 收藏列表
	 * @parameter string $fields 字段
	 * @parameter int $page 页码
	 * @parameter int $page_size 分页大小
	 */
	public function listAction(){
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
			$fields = FieldHelper::process($fields, 'feed', Feed::$public_fields);
		}else{
			$fields = Feed::$default_fields;
		}
		
		$favorites = FavoriteModel::model()->getList($fields,
			$this->form()->getData('page', 1),
			$this->form()->getData('page_size', 20));
		
		Response::json($favorites);
	}
}