<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\models\tables\Users;
use fay\core\Response;

/**
 * 用户
 */
class UserController extends ApiController{
	/**
	 * 判断用户名是否可用
	 * 可用返回状态为1，不可用返回0，http状态码均为200
	 * @param string $username 用户名
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
			Response::json();
		}
	}
	
	/**
	 * 判断用户名是否存在
	 * 存在返回状态为1，不存在返回0，http状态码均为200
	 * @param string $username 用户名
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
			Response::json();
		}else{
			Response::json('', 0, '该用户名不存在');
		}
	}
}