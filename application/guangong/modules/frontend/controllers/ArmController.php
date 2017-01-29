<?php
namespace guangong\modules\frontend\controllers;

use guangong\library\FrontController;
use guangong\models\tables\GuangongHoursTable;
use guangong\models\tables\GuangongUserExtraTable;

/**
 * 关公点兵
 */
class ArmController extends FrontController{
	public function index(){
		
		$this->view->render();
	}
	
	/**
	 * 选防区
	 */
	public function setDefence(){
		
		$this->view->render();
	}
	
	/**
	 * 选兵种
	 */
	public function setArm(){
		
		$this->view->render();
	}
	
	/**
	 * 排勤务
	 */
	public function setHour(){
		$user_extra = GuangongUserExtraTable::model()->find($this->current_user);
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