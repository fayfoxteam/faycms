<?php
namespace guangong\modules\api\controllers;

use fay\services\UserService;
use guangong\models\forms\SignUpForm;
use guangong\models\tables\GuangongUserExtraTable;

class UserController extends \cms\modules\api\controllers\UserController{
	/**
	 * 报名参军
	 * @parameter string $mobile
	 * @parameter string $birthday
	 * @parameter int $state
	 * @parameter int $city
	 * @parameter int $district
	 * @parameter string $captcha
	 */
	public function signUp(){
		//登录检查
		$this->checkLogin();
		
		$this->form()->setModel(SignUpForm::model());
		
		if($this->input->post() && $this->form()->check()){
			UserService::service()->update($this->current_user, array(
				'mobile'=>$this->form()->getData('mobile'),
			));
			
			GuangongUserExtraTable::model()->update(array(
				'birthday'=>$this->form()->getData('birthday'),
				'state'=>$this->form()->getData('state'),
				'city'=>$this->form()->getData('city'),
				'district'=>$this->form()->getData('district'),
			), array(
				'user_id = ?'=>$this->current_user,
			));
		}
	}
	
	/**
	 * 出勤
	 */
	public function attendance(){
		//登录检查
		$this->checkLogin();
		
		
	}
}