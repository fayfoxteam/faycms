<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\services\UserService;
use guangong\models\forms\RegisterForm;

class UserController extends ApiController{
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
		
		$this->form()->setModel(RegisterForm::model());
		
		if($this->input->post() && $this->form()->check()){
			UserService::service()->update($this->current_user, array(
				'mobile'=>$this->form()->getData('mobile'),
			));
			
			
		}
	}
}