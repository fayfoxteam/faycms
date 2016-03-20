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
	
	/**
	 * 点赞
	 * @param int $id 动态ID
	 */
	public function like(){
		
	}
}