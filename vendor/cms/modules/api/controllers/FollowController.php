<?php
namespace cms\modules\api\controllers;

use cms\library\UserController;
use fay\core\Response;
use fay\models\user\Follow;

class FollowController extends UserController{
	/**
	 * 关注一个用户
	 * @param int $user_id
	 */
	public function follow(){
		if($this->form()->setRules(array(
			array(array('user_id'), 'required'),
			array(array('user_id'), 'int', array('min'=>0)),
			array(array('user_id'), 'exist', array('table'=>'users', 'field'=>'id')),
		))->setFilters(array(
			'user_id'=>'intval',
			'follow_from'=>'trim',
		))->setLabels(array(
			'user_id'=>'用户ID',
		))->check()){
			$user_id = $this->form()->getData('user_id');
			if($user_id == $this->current_user){
				Response::notify('error', array(
					'message'=>'您不能关注自己',
					'code'=>'app-error:can-not-follow-yourself',
				));
			}
			
			if(Follow::isFollow($user_id)){
				Response::notify('error', array(
					'message'=>'您已关注过该用户',
					'code'=>'app-error:already-followed',
				));
			}
			
			Follow::follow($user_id, $this->form()->getData('follow_from'));
			Response::notify('success', '关注成功');
		}else{
			$error = $this->form()->getFirstError();
			Response::notify('error', array(
				'message'=>$error['message'],
				'code'=>$error['code'],
			));
		}
		
	}
	
	/**
	 * 取消关注一个用户
	 * @param int $user_id
	 */
	public function unfollow(){
		if($this->form()->setRules(array(
			array(array('user_id'), 'required'),
			array(array('user_id'), 'int', array('min'=>0)),
			array(array('user_id'), 'exist', array('table'=>'users', 'field'=>'id')),
		))->setFilters(array(
			'user_id'=>'intval',
		))->setLabels(array(
			'user_id'=>'用户ID',
		))->check()){
			$user_id = $this->form()->getData('user_id');
			
			if(!Follow::isFollow($user_id)){
				Response::notify('error', array(
					'message'=>'您未关注过该用户',
					'code'=>'app-error:not-followed',
				));
			}
			
			Follow::unfollow($user_id);
			Response::notify('success', '取消关注成功');
		}else{
			$error = $this->form()->getFirstError();
			Response::notify('error', array(
				'message'=>$error['message'],
				'code'=>$error['code'],
			));
		}
	}
}