<?php
namespace cms\modules\api\controllers;

use cms\library\UserController;
use fay\core\Response;
use fay\services\Follow;

class FollowController extends UserController{
	/**
	 * 关注一个用户
	 * @param int $user_id
	 */
	public function follow(){
		if($this->form()->setRules(array(
			array(array('user_id'), 'required'),
			array(array('user_id'), 'int', array('min'=>1)),
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
			
			Follow::follow($user_id, $this->form()->getData('follow_from', 'trim', ''));
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
			array(array('user_id'), 'int', array('min'=>1)),
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
	
	/**
	 * 判断当前登录用户是否关注指定用户
	 * @param int $user_id
	 */
	public function isFollow(){
		if($this->form()->setRules(array(
			array(array('user_id'), 'required'),
			array(array('user_id'), 'int', array('min'=>1)),
		))->setFilters(array(
			'user_id'=>'intval',
		))->setLabels(array(
			'user_id'=>'用户ID',
		))->check()){
			$user_id = $this->form()->getData('user_id');
				
			if($is_follow = Follow::isFollow($user_id)){
				Response::notify('success', array('data'=>$is_follow, 'message'=>'已关注', 'code'=>'followed'));
			}else{
				Response::notify('success', array('data'=>0, 'message'=>'未关注', 'code'=>'unfollowed'));
			}
		}else{
			$error = $this->form()->getFirstError();
			Response::notify('error', array(
				'message'=>$error['message'],
				'code'=>$error['code'],
			));
		}
	}
	
	/**
	 * 批量判断当前用户与多个用户的关注关系
	 * @param array|string $user_ids 用户ID，可以是数组的方式传入，也可以逗号分隔传入
	 */
	public function mIsFollow(){
		if($this->form()->setRules(array(
			array(array('user_ids'), 'required'),
			array(array('user_ids'), 'int'),
		))->setLabels(array(
			'user_ids'=>'用户ID',
		))->check()){
			$user_ids = $this->form()->getData('user_ids');
			if(!is_array($user_ids)){
				$user_ids = explode(',', str_replace(' ', '', $user_ids));
			}
			
			if($is_follow = Follow::mIsFollow($user_ids)){
				Response::notify('success', array('data'=>$is_follow));
			}
		}else{
			$error = $this->form()->getFirstError();
			Response::notify('error', array(
				'message'=>$error['message'],
				'code'=>$error['code'],
			));
		}
	}
}