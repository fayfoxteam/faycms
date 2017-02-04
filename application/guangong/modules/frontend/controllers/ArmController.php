<?php
namespace guangong\modules\frontend\controllers;

use fay\services\FileService;
use guangong\library\FrontController;
use guangong\models\tables\GuangongArmsTable;
use guangong\models\tables\GuangongDefenceAreasTable;
use guangong\models\tables\GuangongHoursTable;
use guangong\models\tables\GuangongUserExtraTable;

/**
 * 关公点兵
 */
class ArmController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->checkLogin();
	}
	
	public function index(){
		
		$this->view->render();
	}
	
	/**
	 * 选防区
	 */
	public function setDefence(){
		$user_extra = GuangongUserExtraTable::model()->find($this->current_user, 'defence_area_id');
		if($user_extra['arm_id']){
			$defence = GuangongDefenceAreasTable::model()->find($user_extra['defence_area_id']);
			$this->view->defence = $defence;
		}else{
			$this->view->defence = array();
		}
		$this->view->render();
	}
	
	/**
	 * 选兵种
	 */
	public function setArm(){
		$user_extra = GuangongUserExtraTable::model()->find($this->current_user, 'arm_id');
		if($user_extra['arm_id']){
			$arm = GuangongArmsTable::model()->find($user_extra['arm_id']);
			$arm['picture'] = FileService::service()->get($arm['picture']);
			$this->view->arm = $arm;
		}else{
			$this->view->arm = array();
		}
		$this->view->render();
	}
	
	/**
	 * 排勤务
	 */
	public function setHour(){
		$user_extra = GuangongUserExtraTable::model()->find($this->current_user, 'hour_id');
		if($user_extra['hour_id']){
			$this->view->hour = GuangongHoursTable::model()->find($user_extra['hour_id']);
		}else{
			$this->view->hour = array();
		}
		$this->view->render();
	}
	
	/**
	 * 录军籍
	 */
	public function info(){
		
		$this->view->render();
	}
	
	/**
	 * 履军职
	 */
	public function job(){
		
		$this->view->render();
	}
}