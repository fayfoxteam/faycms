<?php
namespace guangong\modules\api\controllers;

use fay\core\Response;
use fay\core\Sql;
use fay\models\tables\UsersTable;
use fay\services\file\FileService;
use fay\services\user\UserService;
use guangong\helpers\UserHelper;
use guangong\models\forms\SignUpForm;
use guangong\models\tables\GuangongUserExtraTable;
use guangong\services\AttendanceService;

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
				'birthday'=>date('Y-m-d', strtotime($this->form()->getData('birthday'))),
				'state'=>$this->form()->getData('state'),
				'city'=>$this->form()->getData('city'),
				'sign_up_time'=>$this->current_time,
			), array(
				'user_id = ?'=>$this->current_user,
			));
			
			UserService::service()->update($this->current_user, array(
				'realname'=>$this->form()->getData('realname'),
			));
			
			Response::notify('success', '注册成功');
		}
	}
	
	/**
	 * 出勤
	 */
	public function attendance(){
		die;//要通过做任务来出勤。。
		//登录检查
		$this->checkLogin();
		
		AttendanceService::service()->attend($this->current_user);
		
		Response::notify('success', '操作成功');
	}
	
	/**
	 * 军籍
	 */
	public function info(){
		$user = UsersTable::model()->find($this->current_user, 'id,avatar,mobile');
		$user['avatar'] = FileService::get($user['avatar'], array(
			'spare'=>'avatar',
		));
		
		$sql = new Sql();
		$user_extra = $sql->from(array('ue'=>'guangong_user_extra'), array('birthday', 'sign_up_time', 'rank_id'))
			->joinLeft(array('r1'=>'regions'), 'ue.state = r1.id', array('name AS state_name'))
			->joinLeft(array('r2'=>'regions'), 'ue.city = r2.id', array('name AS city_name'))
			->joinLeft(array('r3'=>'regions'), 'ue.district = r3.id', array('name AS district_name'))
			->joinLeft(array('a'=>'guangong_arms'), 'ue.arm_id = a.id', array('name AS arm_name'))
			->joinLeft(array('r'=>'guangong_ranks'), 'ue.rank_id = r.id', array('captain AS rank_name'))
			->joinLeft(array('d'=>'guangong_defence_areas'), 'ue.defence_area_id = d.id', array('name AS defence_area_name'))
			->where('ue.user_id = ?', $this->current_user)
			->fetchRow();
		
		foreach($user_extra as &$e){
			if($e === null){
				//把null格式化为空字符串
				$e = '';
			}
		}
		
		Response::json(array(
			'user'=>$user,
			'extra'=>$user_extra,
			'code'=>UserHelper::getCode($this->current_user),
		));
	}
}