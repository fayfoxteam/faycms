<?php
namespace w\modules\frontend\controllers;

use fay\core\Loader;

use w\library\FrontController;
use fay\models\Sms;

class IndexController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
		
		$this->layout->current_header_menu = 'home';
	}
	
	public function index(){
        //test
        //test1
		//UcpaasApi::getInstance()->getDevinfo();
//        Sms::getInstance()->getDevinfo();
		dump(Sms::send('18667357613','123123,3','8538'));
		$this->view->render();
	}
	
}