<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\models\tables\Posts;
use fay\serices\post\Favorite;
use fay\core\Response;

/**
 * 文章收藏
 */
class PostFavoriteController extends ApiController{
	/**
	 * 收藏
	 */
	public function add(){
		//登录检查
		$this->checkLogin();
		
		//表单验证
		$this->form()->setRules(array(
			array(array('post_id'), 'required'),
			array('post_id', 'int', array('min'=>1)),
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
			'trackid'=>'trim',
		))->setLabels(array(
			'post_id'=>'文章ID',
		))->check();
		
		$post_id = $this->form()->getData('post_id');
		
		if(!Favorite::isFavorited($post_id)){
			Response::notify('error', array(
				'message'=>'您已收藏过该文章',
				'code'=>'already-favorited',
			));
		}
		
		Favorite::favorite($post_id, $this->form()->getData('trackid', ''));
	}
	
	/**
	 * 取消收藏
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
			'trackid'=>'trim',
		))->setLabels(array(
			'post_id'=>'文章ID',
		))->check();
	}
	
	/**
	 * 收藏列表
	 */
	public function listAction(){
		
	}
}