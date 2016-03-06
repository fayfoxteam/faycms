<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;

class FeedController extends ApiController{
	public function create(){
		$this->checkLogin();
		
	}
}