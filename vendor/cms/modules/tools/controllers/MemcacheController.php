<?php
namespace cms\modules\tools\controllers;

use cms\library\ToolsController;
use fay\core\Response;

class MemcacheController extends ToolsController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'memcache';
	
		//登陆检查，仅超级管理员可访问本模块
		$this->isLogin();
	}
	
	public function index(){
		$this->layout->subtitle = 'Memcache';
		
		$this->layout->sublink = array(
			'uri'=>array('tools/memcache/flush'),
			'text'=>'清空缓存',
		);
		
		//单服务器模式
		$this->view->slabs = current($this->cache->memcache()->getExtendedStats('slabs'));
	
		$this->view->render();
	}
	
	public function delete(){
		$this->cache->delete($this->input->get('key'));
		Response::goback();
	}
	
	public function flush(){
		$this->cache->memcache()->flush();
		Response::goback();
	}
}