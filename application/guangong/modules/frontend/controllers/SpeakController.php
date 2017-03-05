<?php
namespace guangong\modules\frontend\controllers;

use fay\helpers\ArrayHelper;
use fay\models\tables\RegionsTable;
use fay\services\user\UserService;
use guangong\library\FrontController;
use guangong\models\forms\SignUpForm;
use guangong\models\tables\GuangongArmsTable;
use guangong\models\tables\GuangongUserExtraTable;

/**
 * 天下招募令
 */
class SpeakController extends FrontController{
	public function __construct()
	{
		parent::__construct();
		
		$this->checkLogin();
		$this->layout->title = '军团活动';
	}
	
	public function index(){
		
	}
}