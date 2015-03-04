<?php
namespace cms\modules\install\controllers;

use cms\library\InstallController;
use fay\models\tables\Users;
use fay\helpers\String;
use fay\models\Option;
use fay\models\File;
use fay\core\Response;
use fay\core\Db;
use fay\core\Exception;

class IndexController extends InstallController{
	public function __construct(){
		parent::__construct();
		$this->db = Db::getInstance();
	}
	
	public function index(){
		$this->view->render();
	}
	
	public function checkSystem(){
		$this->isInstalled();

		// /uploads
		if(is_writable(BASEPATH.'../uploads')){
			$uploads = true;
		}else{
			//尝试创建
			File::createFolder(BASEPATH.'../uploads');
			if(is_writable(BASEPATH.'../uploads')){
				$uploads = true;
			}else{
				$uploads = false;
			}
		}
		// /public/uploads
		if(is_writable(BASEPATH.'uploads')){
			$public_uploads = true;
		}else{
			//尝试创建
			File::createFolder(BASEPATH.'uploads');
			if(is_writable(BASEPATH.'uploads')){
				$public_uploads = true;
			}else{
				$public_uploads = false;
			}
		}
		// /public/uploads
		if(is_writable(APPLICATION_PATH . 'runtimes')){
			$runtimes = true;
		}else{
			//尝试创建
			File::createFolder(APPLICATION_PATH . 'runtimes');
			if(is_writable(APPLICATION_PATH . 'runtimes')){
				$runtimes = true;
			}else{
				$runtimes = false;
			}
		}
		$this->view->writable = array(
			'/uploads'=>$uploads,
			'/public/uploads'=>$public_uploads,
			'/application/'.APPLICATION.'/runtimes'=>$runtimes,
		);
		
		$this->view->extensions = get_loaded_extensions();
		$this->view->render();
	}
	
	public function doing(){
		$this->isInstalled();

		$this->view->render();
	}
	
	public function settings(){
		if(Users::model()->fetchRow('role = '.Users::ROLE_SUPERADMIN)){
			Response::redirect('a');
		}
		if($this->input->post()){
			$salt = String::random('alnum', 5);
			$password = $this->input->post('password');
			$password = md5(md5($password).$salt);
			Users::model()->insert(array(
				'username'=>$this->input->post('username'),
				'password'=>$password,
				'salt'=>$salt,
				'role'=>Users::ROLE_SUPERADMIN,
				'reg_time'=>$this->current_time,
				'status'=>Users::STATUS_VERIFIED,
			));
			
			Option::set('sitename', $this->input->post('sitename'));
			Response::redirect('a');
		}
		$this->view->render();
	}
	
	private function isInstalled(){
		$this->config->set('session_namespace', $this->config->get('session_namespace').'_admin');
		
		$tbl_user = $this->db->fetchRow("SHOW TABLES LIKE '{$this->db->users}'");
		$this->view->installed = !!$tbl_user;
		
		if($this->session->get('role') != Users::ROLE_SUPERADMIN && $this->view->installed){
			throw new Exception('系统检测到users表已存在，我们将此作为系统数据库已成功安装的依据。<br>系统不允许重复安装，除非您先用超级管理员身份登陆后台！');
		}
	}
}