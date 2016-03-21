<?php
namespace cms\modules\api\controllers;

use cms\library\UserController;
use fay\services\post\Favorite;
use fay\core\Response;
use fay\models\Post;

/**
 * 文章收藏
 */
class PostFavoriteController extends UserController{
	/**
	 * 收藏
	 */
	public function add(){
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
		
		if(Favorite::isFavorited($post_id)){
			Response::notify('error', array(
				'message'=>'您已收藏过该文章',
				'code'=>'already-favorited',
			));
		}
		
		Favorite::add($post_id, $this->form()->getData('trackid', ''));
		
		Response::notify('success', '收藏成功');
	}
	
	/**
	 * 取消收藏
	 */
	public function remove(){
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
		
		if(!Favorite::isFavorited($post_id)){
			Response::notify('error', array(
				'message'=>'您未收藏过该文章',
				'code'=>'not-favorited',
			));
		}
		
		Favorite::remove($post_id);
		
		Response::notify('success', '移除收藏成功');
	}
	
	/**
	 * 收藏列表
	 */
	public function listAction(){
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
		
		
	}
}