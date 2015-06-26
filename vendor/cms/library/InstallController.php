<?php
namespace cms\library;

use fay\core\Controller;
use fay\core\Exception;
use fay\models\Log;
use fay\helpers\String;
use fay\core\Uri;

class InstallController extends Controller{
	/**
	 * 随机字符串校验，防止重复提交
	 */
	public $once;
	
	public function __construct(){
		parent::__construct();
		
		if(Uri::getInstance()->router != 'install/index/index' &&
			$this->input->get('once') != $this->session->get('once', $this->config->get('session_namespace').'_install')){
			@Log::set('admin:action:login.success', array(
				'user_once'=>$this->input->get('once'),
				'system_once'=>$this->session->get('once', $this->config->get('session_namespace').'_install'),
			));
			throw new Exception('异常的请求');
		}
		
		$random = String::random();
		$this->session->set('once', $random);
		$this->once = $random;
		
		//屏蔽测试堆栈
		$this->config->set('debug', false);
		
		$this->layout_template = 'default';
	}
}