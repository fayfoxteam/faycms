<?php
namespace siwi\modules\user\controllers;

use siwi\library\UserController;
use fay\models\tables\Favourites;

class FavouriteController extends UserController{
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
		if(!Favourites::model()->find(array($this->current_user, $id))){
			Favourites::model()->insert(array(
				'user_id'=>$this->current_user,
				'post_id'=>$id,
				'create_time'=>$this->current_time,
			));
			echo json_encode(array(
				'status'=>1,
			));
		}else{
			echo json_encode(array(
				'status'=>0,
				'error_code'=>'already-favored',
				'message'=>'已收藏此文章',
			));
		}
	}
	
	public function remove(){
		$id = $this->input->get('id', 'intval');
		if(Favourites::model()->find(array($this->current_user, $id))){
			Favourites::model()->delete(array(
				'user_id'=>$this->current_user,
				'post_id'=>$id,
			));
			echo json_encode(array(
				'status'=>1,
			));
		}else{
			echo json_encode(array(
				'status'=>0,
				'error_code'=>'unfavored',
				'message'=>'未收藏此文章',
			));
		}
	}
	
}