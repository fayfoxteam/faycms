<?php
namespace cms\modules\install\controllers;

use cms\library\InstallController;
use fay\models\tables\Users;
use fay\services\Option;
use fay\services\File;
use fay\core\Response;
use fay\core\Db;
use fay\core\Exception;
use fay\helpers\Request;
use fay\models\tables\Roles;
use fay\services\User;

class IndexController extends InstallController{
	public function __construct(){
		parent::__construct();
		$this->db = Db::getInstance();
	}
	
	public function index(){
		$this->view->render();
	}
	
	public function checkSystem(){
		$is_installed = $this->isInstalled();
		
		if($is_installed == 'installation-completed'){
			//全部安装已完成
			throw new Exception('程序已完成安装，若要重新安装，请删除当前application下的runtimes/installed.lock文件后重试');
		}else if($is_installed == 'database-completed'){
			//数据库已初始化，跳转至设置超级管理员界面
			Response::redirect('install/index/settings', array(
				'_token'=>$this->getToken(),
			));
		}else{
			//其它情况下（例如数据库创建了一半）全新安装，环境检查
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
	}
	
	public function database(){
		try{
			$this->checkToken();
		}catch(Exception $e){
			Response::redirect('install/index/index');
		}
		
		//检测安装进度
		$is_installed = $this->isInstalled();
		
		if($is_installed == 'installation-completed'){
			//全部安装已完成
			throw new Exception('程序已完成安装！若要重新安装，请删除当前application下的runtimes/installed.lock文件后重试');
		}else if($is_installed == 'database-completed'){
			//数据库安装完成，跳转到用户设置界面
			Response::redirect('install/index/settings', array(
				'_token'=>$this->getToken(),
			));
		}else{
			//其它情况下（例如数据库创建了一半）重新创建数据库
			$this->view->render();
		}
	}
	
	public function settings(){
		try{
			$this->checkToken();
		}catch(Exception $e){
			Response::redirect('install/index/index');
		}
		
		//检测安装进度
		$is_installed = $this->isInstalled();
		
		if($is_installed == 'installation-completed'){
			//全部安装已完成
			throw new Exception('程序已完成安装！若要重新安装，请删除当前application下的runtimes/installed.lock文件后重试');
		}else if($is_installed == 'database-completed'){
			//数据库已初始化，跳转至设置超级管理员界面
			if($this->input->post()){
				$user_id = User::service()->create(array(
					'username'=>$this->input->post('username', 'trim'),
					'password'=>$this->input->post('password'),
					'nickname'=>'系统管理员',//@todo 这里先默认一个，以后再完善下安装程序的界面
					'status'=>Users::STATUS_VERIFIED,
				), array(
					'profile'=>array(
						'trackid'=>'install',
					),
					'roles'=>array(
						Roles::ITEM_SUPER_ADMIN,
					)
				), 1);
				Option::set('site:sitename', $this->input->post('site:sitename', 'trim'));
				
				file_put_contents(APPLICATION_PATH . 'runtimes/installed.lock', "\r\n" . date('Y-m-d H:i:s [') . Request::getIP() . "] \r\ninstallation-completed", FILE_APPEND);
				
				Response::redirect('a');
			}
			$this->view->render();
		}else{
			//其它状态（可能是数据库装一半断掉了）重新安装
			Response::redirect('install/index/index');
		}
	}
	
	private function isInstalled(){
		if(file_exists(APPLICATION_PATH . 'runtimes/installed.lock')){
			$installed_file = file(APPLICATION_PATH . 'runtimes/installed.lock');
			return array_pop($installed_file);
		}else{
			return 'new';
		}
	}
}