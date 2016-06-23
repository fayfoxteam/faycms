<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\services\Follow;
use fay\helpers\FieldHelper;
use fay\core\HttpException;

/**
 * 关注
 */
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
	 * @parameter int $user_id
	 * @parameter string $trackid 追踪ID
	 */
	public function add(){
		//登录检查
		$this->checkLogin();
		
		//表单验证
		$this->form()->setRules(array(
			array(array('user_id'), 'required'),
			array('user_id', 'int', array('min'=>1)),
			array('user_id', 'exist', array('table'=>'users', 'field'=>'id')),
			array('user_id', 'compare', array(
				'compare_value'=>$this->current_user,
				'operator'=>'!=',
				'code'=>'can-not-follow-yourself',
				'message'=>'您不能关注自己',
			)),
			array('fields', 'fields'),
		))->setFilters(array(
			'user_id'=>'intval',
			'trackid'=>'trim',
		))->setLabels(array(
			'user_id'=>'用户ID',
		))->check();
		
		$user_id = $this->form()->getData('user_id');
		
		if(Follow::isFollow($user_id)){
			Response::notify('error', array(
				'message'=>'您已关注过该用户',
				'code'=>'already-followed',
			));
		}
		
		Follow::add($user_id, $this->form()->getData('trackid', ''));
		Response::notify('success', '关注成功');
	}
	
	/**
	 * 取消关注一个用户
	 * @parameter int $user_id
	 */
	public function remove(){
		//登录检查
		$this->checkLogin();
		
		//表单验证
		$this->form()->setRules(array(
			array(array('user_id'), 'required'),
			array(array('user_id'), 'int', array('min'=>1)),
			array(array('user_id'), 'exist', array('table'=>'users', 'field'=>'id')),
		))->setFilters(array(
			'user_id'=>'intval',
		))->setLabels(array(
			'user_id'=>'用户ID',
		))->check();
		
		$user_id = $this->form()->getData('user_id');
		
		if(!Follow::isFollow($user_id)){
			Response::notify('error', array(
				'message'=>'您未关注过该用户',
				'code'=>'not-followed',
			));
		}
		
		Follow::remove($user_id);
		Response::notify('success', '取消关注成功');
	}
	
	/**
	 * 判断当前登录用户是否关注指定用户
	 * @parameter int $user_id
	 */
	public function isFollow(){
		//登录检查
		$this->checkLogin();
		
		//表单验证
		$this->form()->setRules(array(
			array(array('user_id'), 'required'),
			array(array('user_id'), 'int', array('min'=>1)),
		))->setFilters(array(
			'user_id'=>'intval',
		))->setLabels(array(
			'user_id'=>'用户ID',
		))->check();
		
		$user_id = $this->form()->getData('user_id');
			
		if($is_follow = Follow::isFollow($user_id)){
			Response::notify('success', array('data'=>$is_follow, 'message'=>'已关注', 'code'=>'followed'));
		}else{
			Response::notify('success', array('data'=>0, 'message'=>'未关注', 'code'=>'unfollowed'));
		}
	}
	
	/**
	 * 批量判断当前用户与多个用户的关注关系
	 * @parameter array|string $user_ids 用户ID，可以是数组的方式传入，也可以逗号分隔传入
	 */
	public function mIsFollow(){
		//表单验证
		$this->form()->setRules(array(
			array(array('user_ids'), 'required'),
			array(array('user_ids'), 'int'),
		))->setLabels(array(
			'user_ids'=>'用户ID',
		))->check();
		
		$user_ids = $this->form()->getData('user_ids');
		if(!is_array($user_ids)){
			$user_ids = explode(',', str_replace(' ', '', $user_ids));
		}
		
		if($is_follow = Follow::mIsFollow($user_ids)){
			Response::notify('success', array('data'=>$is_follow));
		}
	}
	
	/**
	 * 粉丝列表
	 * @parameter int $user_id 用户ID
	 * @parameter string $fields 字段
	 * @parameter int $page 页码
	 * @parameter int $page_size 分页大小
	 */
	public function fans(){
		//表单验证
		$this->form()->setRules(array(
			array(array('user_id', 'page', 'page_size'), 'int', array('min'=>1)),
			array(array('user_id'), 'exist', array('table'=>'users', 'field'=>'id')),
			array('fields', 'fields'),
		))->setFilters(array(
			'user_id'=>'intval',
			'page'=>'intval',
			'page_size'=>'intval',
			'fields'=>'trim',
		))->setLabels(array(
			'user_id'=>'用户ID',
			'page'=>'页码',
			'page_size'=>'分页大小',
		))->check();
		
		$user_id = $this->form()->getData('user_id', $this->current_user);
		if(!$user_id){
			throw new HttpException('未指定用户', 404, 'user_id:not-found');
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
	}
	
	/**
	 * 关注列表
	 * @parameter int $user_id 用户ID
	 * @parameter string $fields 字段
	 * @parameter int $page 页码
	 * @parameter int $page_size 分页大小
	 */
	public function follows(){
		//表单验证
		$this->form()->setRules(array(
			array(array('user_id', 'page', 'page_size'), 'int', array('min'=>1)),
			array(array('user_id'), 'exist', array('table'=>'users', 'field'=>'id')),
			array('fields', 'fields'),
		))->setFilters(array(
			'user_id'=>'intval',
			'page'=>'intval',
			'page_size'=>'intval',
			'fields'=>'trim',
		))->setLabels(array(
			'user_id'=>'用户ID',
			'page'=>'页码',
			'page_size'=>'分页大小',
		))->check();
		
		$user_id = $this->form()->getData('user_id', $this->current_user);
		if(!$user_id){
			throw new HttpException('未指定用户', 404, 'user_id:not-found');
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
	}
}