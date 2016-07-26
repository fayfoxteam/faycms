<?php
namespace cms\modules\tools\controllers;

use cms\library\ToolsController;
use fay\core\Loader;
use fay\core\Response;

class FunctionController extends ToolsController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'function';
	}
	
	public function unserialize(){
		$this->layout->subtitle = 'unserialize';
		
		$this->view->result = unserialize($this->input->post('key'));
		$this->view->render();
	}
	
	public function evalAction(){
		//登陆检查，仅超级管理员可访问本模块
		$this->isLogin();
		
		$this->layout->subtitle = 'eval';
		$this->view->key = $this->input->post('key');
		$this->view->render();
	}
	
	public function json(){
		$this->layout->subtitle = 'JSON';
		
		$this->view->render();
	}
	
	public function url(){
		$this->layout->subtitle = 'urldecode';
		
		$this->view->result = urldecode($this->input->post('key'));
		$this->view->render();
	}
	
	public function date(){
		$this->layout->subtitle = 'date';
		
		$this->view->render();
	}
	
	public function string(){
		$this->layout->subtitle = 'string';
		
		$this->view->render();
	}
	
	public function ip(){
		$this->layout->subtitle = 'ip';
		
		//引入IP地址库
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		$this->view->render();
	}
	
	public function doEval(){
		$code = $this->input->post('code', '', '');
		echo eval('?>'. $code);
	}
	
	/**
	 * 对用户输入的php代码进行json_decode后返回
	 * 返回的是php代码
	 */
	public function jsonDecode(){
		Response::json(array(
			'code'=>var_export(json_decode($this->input->request('code'), true), true)
		));
	}
	
	/**
	 * 对用户输入的php代码进行json_encode后返回
	 * 这个方法是有风险的，因为用了eval函数
	 */
	public function jsonEncode(){
		//登陆检查，仅超级管理员可访问本模块
		$this->isLogin();
		
		$array = $this->input->request('code');
		if(defined(JSON_UNESCAPED_UNICODE)){
			Response::json(array(
				'code'=>json_encode(eval('return '.$array.';'), JSON_UNESCAPED_UNICODE)
			));
		}else{
			//不做复杂处理，低版本php直接返回unicode后的中文
			Response::json(array(
				'code'=>json_encode(eval('return '.$array.';'))
			));
		}
	}
}