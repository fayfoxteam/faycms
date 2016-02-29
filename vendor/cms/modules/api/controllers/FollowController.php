<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\services\Follow;
use fay\helpers\FieldHelper;

class FollowController extends ApiController{
	/**
	 * 默认返回字段
	 */
	private $default_fields = array(
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
		'follows'=>array(
			'relation'
		),
	);
	
	/**
	 * 可选字段
	*/
	private $allowed_fields = array(
		'user'=>array(
			'id', 'username', 'nickname', 'avatar', 'roles'=>array(
				'id', 'title',
			),
		),
		'follows'=>array(
			'relation', 'create_time',
		),
	);
	
	/**
	 * 关注一个用户
	 * @param int $user_id
	 */
	public function follow(){
		$this->checkLogin();
		if($this->form()->setRules(array(
			array(array('user_id'), 'required'),
			array(array('user_id'), 'int', array('min'=>1)),
			array(array('user_id'), 'exist', array('table'=>'users', 'field'=>'id')),
		))->setFilters(array(
			'user_id'=>'intval',
			'trackid'=>'trim',
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
			
			Follow::follow($user_id, $this->form()->getData('trackid', 'trim', ''));
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
		$this->checkLogin();
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
		$this->checkLogin();
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
		$this->checkLogin();
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
	
	/**
	 * 粉丝列表
	 * @param int $user_id 用户ID
	 * @param string $fields 字段
	 * @param int $page 页码
	 * @param int $page_size 分页大小
	 */
	public function fans(){
		if($this->form()->setRules(array(
			array(array('user_id', 'page', 'page_size'), 'int', array('min'=>1)),
			array(array('user_id'), 'exist', array('table'=>'users', 'field'=>'id')),
		))->setFilters(array(
			'user_id'=>'intval',
			'page'=>'intval',
			'page_size'=>'intval',
			'fields'=>'trim',
		))->setLabels(array(
			'user_id'=>'用户ID',
			'page'=>'页码',
			'page_size'=>'分页大小',
		))->check()){
			$user_id = $this->form()->getData('user_id', $this->current_user);
			if(!$user_id){
				Response::notify('error', array(
					'message'=>'未指定用户',
					'code'=>'user_id:not-found',
				));
			}
			
			$fields = $this->form()->getData('fields');
			if($fields){
				//过滤字段，移除那些不允许的字段
				$fields = FieldHelper::process($fields, 'follows', $this->allowed_fields);
			}else{
				$fields = $this->default_fields;
			}
			
			$fans = Follow::fans($user_id,
				$fields,
				$this->form()->getData('page', 1),
				$this->form()->getData('page_size', 20));
			Response::json($fans);
		}else{
			$error = $this->form()->getFirstError();
			Response::notify('error', array(
				'message'=>$error['message'],
				'code'=>$error['code'],
			));
		}
	}
	
	/**
	 * 关注列表
	 * @param int $user_id 用户ID
	 * @param string $fields 字段
	 * @param int $page 页码
	 * @param int $page_size 分页大小
	 */
	public function follows(){
		if($this->form()->setRules(array(
			array(array('user_id', 'page', 'page_size'), 'int', array('min'=>1)),
			array(array('user_id'), 'exist', array('table'=>'users', 'field'=>'id')),
		))->setFilters(array(
			'user_id'=>'intval',
			'page'=>'intval',
			'page_size'=>'intval',
			'fields'=>'trim',
		))->setLabels(array(
			'user_id'=>'用户ID',
			'page'=>'页码',
			'page_size'=>'分页大小',
		))->check()){
			$user_id = $this->form()->getData('user_id', $this->current_user);
			if(!$user_id){
				Response::notify('error', array(
					'message'=>'未指定用户',
					'code'=>'user_id:not-found',
				));
			}
			
			$fields = $this->form()->getData('fields');
			if($fields){
				//过滤字段，移除那些不允许的字段
				$fields = FieldHelper::process($fields, 'follows', $this->allowed_fields);
			}else{
				$fields = $this->default_fields;
			}
				
			$follows = Follow::follows($user_id,
				$fields,
				$this->form()->getData('page', 1),
				$this->form()->getData('page_size', 20));
			Response::json($follows);
		}else{
			$error = $this->form()->getFirstError();
			Response::notify('error', array(
				'message'=>$error['message'],
				'code'=>$error['code'],
			));
		}
	}
}