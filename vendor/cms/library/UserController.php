<?php
namespace cms\library;

use fay\core\Response;
/**
 * 登录用户可访问的API
 */
class UserController extends ApiController{
	public function __construct(){
		parent::__construct();
		
		if(!$this->current_user){
			Response::json('', 0, '请先登录', 'login-request');
		}
	}
}