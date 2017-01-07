<?php
namespace siwi\modules\user\controllers;

use siwi\library\UserController;
use fay\models\tables\FollowersTable;
use fay\models\tables\UsersTable;
use fay\core\Response;

class FollowController extends UserController{
	/**
	 * 添加关注
	 */
	public function create(){
		$user_id = $this->input->get('id', 'intval');
		
		if($this->current_user == $user_id){
			Response::notify('error', array(
				'message'=>'您不能关注自己',
				'error_code'=>'can-not-follow-yourself',
			));
		}
		
		if(!UsersTable::model()->find($user_id, 'id')){
			Response::notify('error', array(
				'message'=>'用户不存在',
				'error_code'=>'user-not-exist',
			));
		}
		
		if(FollowersTable::model()->fetchRow(array(
			'follower = '.$this->current_user,
			'user_id'=>$user_id,
		))){
			Response::notify('error', array(
				'message'=>'已关注此用户，请勿重复操作',
				'error_code'=>'already-followed',
			));
		}
		
		FollowersTable::model()->insert(array(
			'user_id'=>$user_id,
			'follower'=>$this->current_user,
			'create_time'=>$this->current_time,
		));
		
		Response::notify('success', array(
			'message'=>'关注成功',
		));
	}
	
	/**
	 * 取消关注
	 */
	public function delete(){
		$user_id = $this->input->get('id', 'intval');

		if(!UsersTable::model()->find($user_id, 'id')){
			Response::notify('error', array(
				'message'=>'用户不存在',
				'error_code'=>'user-not-exist',
			));
		}
		
		if(!FollowersTable::model()->fetchRow(array(
			'follower = '.$this->current_user,
			'user_id'=>$user_id,
		))){
			Response::notify('error', array(
				'message'=>'未关注此用户',
				'error_code'=>'unfollowed',
			));
		}
		
		FollowersTable::model()->delete(array(
			'follower = '.$this->current_user,
			'user_id'=>$user_id,
		));
		
		Response::notify('success', array(
			'message'=>'取消关注',
		));
		
	}
	
	public function isFollow(){
		$user_id = $this->input->get('id', 'intval');
		
		if(!UsersTable::model()->find($user_id, 'id')){
			Response::notify('error', array(
				'message'=>'用户不存在',
				'error_code'=>'user-not-exist',
			));
		}
		
		if(FollowersTable::model()->fetchRow(array(
			'follower = '.$this->current_user,
			'user_id'=>$user_id,
		))){
			Response::notify('success', array(
				'message'=>'已关注',
				'status'=>1,
			));
		}else{
			Response::notify('success', array(
				'message'=>'未关注',
				'status'=>0,
			));
		}
	}
}