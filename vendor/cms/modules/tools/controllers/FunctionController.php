<?php
namespace cms\modules\tools\controllers;

use cms\library\ToolsController;
use fay\core\Loader;

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
	
	public function urldecode(){
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
}