<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\models\tables\Users;
use fay\core\Response;
use fay\helpers\FieldHelper;
use fay\core\HttpException;
use fay\models\User;

/**
 * 用户
 */
class UserController extends ApiController{
	/**
	 * 判断用户名是否可用
	 * 可用返回状态为1，不可用返回0，http状态码均为200
	 * @parameter string $username 用户名
	 */
	public function isUsernameNotExist(){
		//表单验证
		$this->form()->setRules(array(
			array('username', 'required'),
		))->setFilters(array(
			'username'=>'trim',
		))->setLabels(array(
			'username'=>'用户名',
		))->check();
		
		if(Users::model()->fetchRow(array(
			'username = ?'=>$this->form()->getData('username'),
			'id != ?'=>$this->input->request('id', 'intval', false),
		))){
			Response::json('', 0, '用户名已存在');
		}else{
			Response::json('', 1, '用户名可用');
		}
	}
	
	/**
	 * 判断用户名是否存在
	 * 存在返回状态为1，不存在返回0，http状态码均为200
	 * @parameter string $username 用户名
	 */
	public function isUsernameExist(){
		//表单验证
		$this->form()->setRules(array(
			array('username', 'required'),
		))->setFilters(array(
			'username'=>'trim',
		))->setLabels(array(
			'username'=>'用户名',
		))->check();
		
		if(Users::model()->fetchRow(array(
			'username = ?'=>$this->form()->getData('username'),
			'deleted = 0',
			'id != ?'=>$this->input->request('id', 'intval', false)
		))){
			Response::json('', 1, '用户名存在');
		}else{
			Response::json('', 0, '用户名不存在');
		}
	}
	/**
	 * 判断昵称是否可用
	 * 可用返回状态为1，不可用返回0，http状态码均为200
	 * @parameter string $nickname 昵称
	 */
	public function isNicknameNotExist(){
		//表单验证
		$this->form()->setRules(array(
			array('nickname', 'required'),
		))->setFilters(array(
			'nickname'=>'trim',
		))->setLabels(array(
			'nickname'=>'昵称',
		))->check();
		
		if(Users::model()->fetchRow(array(
			'nickname = ?'=>$this->form()->getData('nickname'),
			'id != ?'=>$this->input->request('id', 'intval', false),
		))){
			Response::json('', 0, '昵称已存在');
		}else{
			Response::json();
		}
	}
	
	/**
	 * 判断昵称是否存在
	 * 存在返回状态为1，不存在返回0，http状态码均为200
	 * @parameter string $nickname 昵称
	 */
	public function isNicknameExist(){
		//表单验证
		$this->form()->setRules(array(
			array('nickname', 'required'),
		))->setFilters(array(
			'nickname'=>'trim',
		))->setLabels(array(
			'nickname'=>'昵称',
		))->check();
		
		if(Users::model()->fetchRow(array(
			'nickname = ?'=>$this->form()->getData('nickname'),
			'deleted = 0',
			'id != ?'=>$this->input->request('id', 'intval', false)
		))){
			Response::json();
		}else{
			Response::json('', 0, '该昵称不存在');
		}
	}
	
	/**
	 * 返回单用户信息
	 */
	public function get(){
		//表单验证
		$this->form()->setRules(array(
			array(array('id'), 'required'),
			array(array('id'), 'int', array('min'=>1)),
			array('fields', 'fields'),
		))->setFilters(array(
			'id'=>'intval',
			'fields'=>'trim',
		))->setLabels(array(
			'id'=>'用户ID',
			'fields'=>'字段',
		))->check();
		
		$id = $this->form()->getData('id');
		$fields = $this->form()->getData('fields');
		
		if($fields){
			//过滤字段，移除那些不允许的字段
			$fields = FieldHelper::process($fields, 'user', User::$public_fields);
		}else{
			//若未指定$fields，取默认值
			$fields = User::$default_fields;
		}
		
		$post = User::model()->get($id, $fields);
		if($post){
			Response::json($post);
		}else{
			throw new HttpException('指定用户不存在');
		}
	}
}