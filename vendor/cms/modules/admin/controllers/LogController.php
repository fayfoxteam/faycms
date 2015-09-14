<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use fay\core\Loader;
use fay\core\Response;

class LogController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'site';
	}
	
	public function index(){
		$this->layout->subtitle = '日志';
		
		$sql = new Sql();
		$sql->from('logs', 'l')
			->joinLeft('users', 'u', 'l.user_id = u.id', 'username')
		;
		
		if($this->input->get('code')){
			$sql->where(array(
				'l.code LIKE ?'=>$this->input->get('code').'%',
			));
		}
		if($this->input->get('type') !== null){
			$sql->where(array(
				'l.type = ?'=>$this->input->get('type', 'intval'),
			));
		}
		
		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'asc' : 'desc';
			$sql->order("{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('l.id DESC');
		}
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>20,
		));
		
		//引入IP地址库
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		$this->view->render();
	}
	
	public function get(){
		$sql = new Sql();
		$log = $sql->from('logs', 'l')
			->joinLeft('users', 'u', 'l.user_id = u.id', 'username')
			->where(array('l.id = ?'=>$this->input->get('id', 'intval')))
			->fetchRow()
		;
		if($log){
			Response::json($log);
		}else{
			Response::json('', 0, '指定日志不存在');
		}
	}
}