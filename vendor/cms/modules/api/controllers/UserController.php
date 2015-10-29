<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\models\tables\Users;
use fay\core\Response;

class UserController extends ApiController{
	public function isUsernameNotExist(){
		$value = $this->input->post('value');
		if(!$value){
			Response::notify('error', '用户名不能为空');
		}
		$conditions = array(
			'username = ?'=>$value,
			'deleted = 0',
		);
		if($this->input->request('id')){
			$conditions['id != ?'] = $this->input->request('id', 'intval');
		}
		$user = Users::model()->fetchRow($conditions);
		if($user){
			Response::notify('error', '用户名已存在');
		}else{
			Response::notify('success');
		}
	}
	
	public function isUsernameExist(){
		$value = $this->input->post('value');
		$conditions = array(
			'username = ?'=>$value,
			'deleted = 0',
		);
		if($this->input->request('id')){
			$conditions['id != ?'] = $this->input->request('id', 'intval');
		}
		$user = Users::model()->fetchRow($conditions);
		if($user){
			Response::notify('success');
		}else{
			Response::notify('error', '该用户名不存在');
		}
	}
}