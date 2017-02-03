<?php

namespace guangong\modules\frontend\controllers;

use fay\helpers\ArrayHelper;
use fay\models\tables\RegionsTable;
use fay\services\user\UserService;
use guangong\library\FrontController;
use guangong\models\forms\SignUpForm;
use guangong\models\tables\GuangongUserExtraTable;

/**
 * 天下招募令
 */
class RecruitController extends FrontController{
	public function index(){
		$this->view->render();
	}
	
	public function step1(){
		
		$this->view->render();
	}
	
	public function step2(){
		
		$this->view->render();
	}
	
	public function step3(){
		if($this->current_user){
			$this->view->user_extra = GuangongUserExtraTable::model()->find($this->current_user);
			$this->view->user = UserService::service()->get($this->current_user, 'id,mobile,avatar');
		}else{
			$this->view->user_extra = array();
			$this->view->user = array();
		}
		
		$this->view->states = ArrayHelper::column(RegionsTable::model()->fetchAll('parent_id = 1', 'id,name'), 'name', 'id');
		
		$this->form()->setModel(SignUpForm::model());
		
		$this->view->render();
	}
	
	public function step4(){
		
		$this->view->render();
	}
}