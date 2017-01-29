<?php
namespace guangong\modules\frontend\controllers;

use guangong\library\FrontController;

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