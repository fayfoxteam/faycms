<?php
namespace siwi\modules\user\controllers;

use siwi\library\UserController;
use fay\models\tables\Likes;
use fay\services\PostService;

class LikeController extends UserController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
		
		$this->layout->current_directory = 'home';
	}
	
	public function index(){
		
		
		$this->view->render();
	}
	
	public function add(){
		$id = $this->input->get('id', 'intval');
		if(!Likes::model()->find(array($this->current_user, $id))){
			Likes::model()->insert(array(
				'user_id'=>$this->current_user,
				'post_id'=>$id,
				'create_time'=>$this->current_time,
			));
			PostService::service()->incLikes($id);
			echo json_encode(array(
				'status'=>1,
			));
		}else{
			echo json_encode(array(
				'status'=>0,
				'error_code'=>'already-liked',
				'message'=>'已赞过此文章',
			));
		}
	}
	
	public function remove(){
		$id = $this->input->get('id', 'intval');
		if(Likes::model()->find(array($this->current_user, $id))){
			Likes::model()->delete(array(
				'user_id'=>$this->current_user,
				'post_id'=>$id,
			));
			PostService::service()->decLikes($id);
			echo json_encode(array(
				'status'=>1,
			));
		}else{
			echo json_encode(array(
				'status'=>0,
				'error_code'=>'unliked',
				'message'=>'未赞此文章',
			));
		}
	}
	
}