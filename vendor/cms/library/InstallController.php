<?php
namespace cms\library;

use fay\core\Controller;

class InstallController extends Controller{
	public function __construct(){
		parent::__construct();
		
		//屏蔽测试堆栈
		$this->config->set('debug', false);
		
		$this->layout_template = 'default';
	}
}