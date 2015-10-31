<?php
namespace cms\modules\tools\controllers;

use cms\library\ToolsController;
use fay\core\Response;

class InputController extends ToolsController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'input';
	}
	
	public function session(){
		\F::session();//默认不开启session
		$this->layout->subtitle = 'SESSION';
		$this->layout->sublink = array(
			'uri'=>array('tools/input/clearsession'),
			'text'=>'清除SESSION',
		);
		$this->view->render();
	}
	
	public function cookie(){
		$this->layout->subtitle = 'COOKIE';
		$this->view->render();
	}
	
	public function server(){
		//登陆检查，仅超级管理员可访问本模块
		$this->isLogin();
		
		$this->layout->subtitle = 'SERVER';
		$this->view->render();
	}
	
	public function get(){
		$this->layout->subtitle = 'GET';
		if($this->input->isAjaxRequest()){
			Response::json($this->input->get());
		}
		$this->view->render();
	}
	
	public function post(){
		$this->layout->subtitle = 'POST';
		if($this->input->isAjaxRequest()){
			Response::json($this->input->get());
		}
		$this->view->render();
	}
	
	public function clearsession(){
		session_destroy();
		Response::goback();
	}
}