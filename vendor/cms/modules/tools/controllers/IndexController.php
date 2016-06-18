<?php
namespace cms\modules\tools\controllers;

use cms\library\ToolsController;
use fay\helpers\Request;
use fay\core\Loader;

class IndexController extends ToolsController{
	
	public function index(){
		$this->layout->subtitle = 'Tools';
		//引入IP地址库
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		//浏览器类型
		$this->view->browser = Request::getBrowser();
		
		$this->view->render();
	}
}