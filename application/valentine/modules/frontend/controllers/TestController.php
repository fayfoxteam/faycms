<?php
namespace valentine\modules\api\controllers;

use cms\library\ApiController;

/**
 * 测试Controller，上线前删除或禁用
 */
class TestController extends ApiController{
	//登录指定用户
	public function setOpenId(){
		\F::session()->set('open_id', 'ohBiqv7DwPPlhtyDF6hH2gpPQkqE');
	}
}