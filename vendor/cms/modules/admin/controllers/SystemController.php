<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Users;
use fay\models\Setting;
use fay\core\Response;

class SystemController extends AdminController{
	public function isCellphoneExist(){
		if(Users::model()->fetchRow(array(
			'cellphone = ?'=>$this->input->post('value', 'trim'),
			'id != ?'=>$this->input->request('id', 'intval')
		))){
			Response::json('', 0, '该手机号码已被注册');
		}else{
			Response::json();
		}
	}
	
	public function isEmailExist(){
		if(Users::model()->fetchRow(array(
			'email = ?'=>$this->input->post('value', 'trim'),
			'id != ?'=>$this->input->request('id', 'intval')
		))){
			Response::json('', 0, '该邮箱已被注册');
		}else{
			Response::json();
		}
	}
	
	public function isUsernameExist(){
		if(Users::model()->fetchRow(array(
			'username = ?'=>$this->input->post('value', 'trim'),
			'id != ?'=>$this->input->request('id', 'intval')
		))){
			Response::json('', 0, '该用户名已被注册');
		}else{
			Response::json();
		}
	}
	
	public function setting(){
		if($this->input->post()){
			if($this->form('setting')
				->setModel(Setting::model())
				->check()){
				$data = $this->form('setting')->getAllData();
				$key = $data['_key'];
				unset($data['_key']);
				Setting::model()->set($key, $data);
				Response::output('success', '设置保存成功');
			}else{
				Response::output('error', '异常的数据格式');
			}
		}else{
			Response::output('error', '无数据被提交');
		}
	}
}