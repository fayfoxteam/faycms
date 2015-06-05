<?php
namespace siwi\plugins;

use fay\core\FBase;

class Dashboard extends FBase{
	public static function run(){
		//操作dashboard，测试用途
		\F::app()->addBox(array(
			'name'=>'test',
			'title'=>'第三方widget测试',
		));
	}
}