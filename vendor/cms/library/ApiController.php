<?php
namespace cms\library;

use fay\core\Controller;
use fay\core\Response;

/**
 * API积累
 */
class ApiController extends Controller{
	public function __construct(){
		parent::__construct();
		
		$this->current_user = \F::session()->get('user.id');
	}
	
	/**
	 * 判断是否已登录
	 * @return bool
	 */
	protected function isLogin(){
		return !!$this->current_user;
	}
	
	/**
	 * 判断是否已登录，若未登录，直接返回需要登录的json
	 */
	protected function checkLogin(){
		if(!$this->isLogin()){
			Response::setStatusHeader(401);
			Response::json('', 0, '请先登录', 'login-request');
		}
	}
}