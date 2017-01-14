<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\services\UserService;
use guangong\models\forms\RegisterForm;

class UserController extends ApiController{
	/**
	 * 注册
	 * @parameter string $mobile
	 * @parameter string $birthday
	 * @parameter int $state
	 * @parameter int $city
	 * @parameter int $district
	 * @parameter string $captcha
	 */
	public function register(){
		$this->form()->setModel(RegisterForm::model());
		
		if($this->input->post() && $this->form()->check()){
			$user_id = UserService::service()->create(array(
				'mobile'=>$this->form()->getData('mobile'),
			));
		}
	}
}