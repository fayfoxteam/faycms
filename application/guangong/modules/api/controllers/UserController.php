<?php
namespace guangong\modules\api\controllers;

use fay\core\Response;
use fay\services\UserService;
use guangong\models\forms\SignUpForm;
use guangong\models\tables\GuangongAttendancesTable;
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
		
		$last_attendance = GuangongAttendancesTable::model()->fetchRow(
			array(
				'user_id = ?'=>$this->current_user,
			),
			'create_date,continuous',
			'id DESC'
		);
		
		if($last_attendance){
			//曾经出勤过
			if($last_attendance['create_date'] == date('Y-m-d', $this->current_time)){
				Response::notify('error', array(
					'message'=>'您今天已经出勤过了，欢迎明天继续',
					'code'=>'already-attended',
				));
			}
			
			if($last_attendance['create_date'] == date('Y-m-d', $this->current_time - 86400)){
				//最后一条出勤记录是昨天
				GuangongAttendancesTable::model()->insert(array(
					'user_id'=>$this->current_user,
					'create_date'=>date('Y-m-d', $this->current_time),
					'create_time'=>$this->current_time,
					'continuous'=>$last_attendance['continuous'] + 1
				));
			}else{
				//出勤中断，重新计数
				GuangongAttendancesTable::model()->insert(array(
					'user_id'=>$this->current_user,
					'create_date'=>date('Y-m-d', $this->current_time),
					'create_time'=>$this->current_time,
					'continuous'=>1
				));
			}
		}else{
			//首次出勤
			GuangongAttendancesTable::model()->insert(array(
				'user_id'=>$this->current_user,
				'create_date'=>date('Y-m-d', $this->current_time),
				'create_time'=>$this->current_time,
				'continuous'=>1
			));
		}
		
		Response::notify('success', '操作成功');
	}
}