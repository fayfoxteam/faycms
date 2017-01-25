<?php
namespace guangong\modules\frontend\controllers;

use guangong\library\FrontController;
use guangong\models\forms\CreateGroupForm;

/**
 * 义结金兰
 */
class GroupController extends FrontController{
	public function index(){
		$this->form()->setModel(CreateGroupForm::model());
		
		$this->view->render();
	}
	
	public function step2(){
		
		
		$this->view->render();
	}
}