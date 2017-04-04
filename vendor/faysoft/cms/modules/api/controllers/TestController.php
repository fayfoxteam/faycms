<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;

/**
 * ping测试
 */
class TestController extends ApiController{
	public function pingAction(){
		die('pong');
	}
}