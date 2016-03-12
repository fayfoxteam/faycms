<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;

/**
 * 动态
 */
class FeedController extends ApiController{
	public function create(){
		$this->checkLogin();
		
	}
}