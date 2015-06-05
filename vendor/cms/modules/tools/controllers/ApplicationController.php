<?php
namespace cms\modules\tools\controllers;

use cms\library\ToolsController;
use fay\models\File;
use fay\models\Category;
use fay\helpers\String;
use fay\models\tables\Users;
use fay\models\Menu;
use fay\models\tables\Categories;
use fay\models\tables\Menus;

class ApplicationController extends ToolsController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'application';
		
		//登陆检查，仅超级管理员可访问本模块
		$this->isLogin();
	}
	
	public function index(){
		$this->layout->subtitle = 'Application List';
		
		$this->view->render();
	}
	
	public function create(){
		$this->layout->subtitle = '创建项目';
		
		$this->flash->set('此工具用于快速创建一个application项目', 'attention');
		if(!is_writable(BASEPATH.'..'.DS.'application')){
			$this->flash->set('application目录不可写！用此功能创建项目，请确保系统对application目录拥有写权限。');
		}
		
		if($this->input->post()){
			$app_name = $this->input->post('name');
			$table_prefix = $this->input->post('table_prefix');
			//创建主配置文件
			$config_file = file_get_contents(SYSTEM_PATH.'cms/modules/tools/views/application/_templates/config/main.txt');
			$config_file = str_replace(array(
				'{{$host}}',
				'{{$user}}',
				'{{$password}}',
				'{{$port}}',
				'{{$dbname}}',
				'{{$table_prefix}}',
				'{{$name}}',
			), array(
				$this->input->post('host'),
				$this->input->post('user'),
				$this->input->post('password'),
				$this->input->post('port', 'intval', 3306),
				$this->input->post('dbname'),
				$table_prefix,
				$app_name,
			), $config_file);
			File::createFile(BASEPATH.'..'.DS.'application/'.$app_name.'/configs/main.php', $config_file);
			
			//创建前端控制器基类
			$front_controller = file_get_contents(SYSTEM_PATH.'cms/modules/tools/views/application/_templates/library/FrontController.txt');
			$front_controller = str_replace('{{$name}}', $app_name, $front_controller);
			File::createFile(BASEPATH.'..'.DS.'application/'.$app_name.'/library/FrontController.php', $front_controller);
			
			//创建默认控制器
			$index_controller = file_get_contents(SYSTEM_PATH.'cms/modules/tools/views/application/_templates/module/IndexController.txt');
			$index_controller = str_replace('{{$name}}', $app_name, $index_controller);
			File::createFile(BASEPATH.'..'.DS.'application/'.$app_name.'/modules/frontend/controllers/IndexController.php', $index_controller);
			
			//创建默认视图
			File::createFile(BASEPATH.'..'.DS.'application/'.$app_name.'/modules/frontend/views/index/index.php', file_get_contents(SYSTEM_PATH.'cms/modules/tools/views/application/_templates/module/index.txt'));
			
			//创建默认layout
			File::createFile(BASEPATH.'..'.DS.'application/'.$app_name.'/modules/frontend/views/layouts/frontend.php', file_get_contents(SYSTEM_PATH.'cms/modules/tools/views/application/_templates/module/frontend.txt'));
			
			//创建默认css
			File::createFile(BASEPATH.'static/'.$app_name.'/css/style.css', file_get_contents(SYSTEM_PATH.'cms/modules/tools/views/application/_templates/static/style.css'));
			
			if($this->input->post('database')){
				//安装数据库
				$this->db = new \cms\library\Db(array(
					'host'=>$this->input->post('host'),
					'user'=>$this->input->post('user'),
					'password'=>$this->input->post('password'),
					'port'=>$this->input->post('port', 'intval', 3306),
					'dbname'=>$this->input->post('dbname'),
					'table_prefix'=>$table_prefix,
				));
				$this->createTables($table_prefix);
				$this->setCities($table_prefix);
				$this->setRegions($table_prefix);
				$this->setCats($table_prefix);
				$this->setMenus($table_prefix);
				$this->setActions($table_prefix);
				$this->setSystem($table_prefix);
				$this->indexCats();
				$this->indexMenus();
				
				$salt = String::random('alnum', 5);
				$password = $this->input->post('user_password');
				$password = md5(md5($password).$salt);
				$this->db->insert('users', array(
					'username'=>$this->input->post('user_username'),
					'password'=>$password,
					'salt'=>$salt,
					'role'=>Users::ROLE_SUPERADMIN,
					'reg_time'=>$this->current_time,
					'status'=>Users::STATUS_VERIFIED,
				));
			}
		}
		
		$this->view->render();
	}
	
	public function isAppNotExist(){
		$value = $this->input->post('value');
		
		$apps = File::getFileList(APPLICATION_PATH.'..');
		foreach($apps as $app){
			if($value == $app['name']){
				echo json_encode(array(
					'status'=>0,
					'message'=>'项目名已存在',
				));
				die;
			}
		}
		echo json_encode(array(
			'status'=>1,
		));
	}
	
	private function createTables($prefix){
		$sql = file_get_contents(__DIR__.'/../../install/data/tables.sql');
		$sql = str_replace(array('{{$prefix}}', '{{$time}}'), array($prefix, $this->current_time), $sql);
		$this->db->execute($sql);
	}
	
	private function setCities($prefix){
		$sql = file_get_contents(__DIR__.'/../../install/data/cities.sql');
		$sql = str_replace(array('{{$prefix}}', '{{$time}}'), array($prefix, $this->current_time), $sql);
		$this->db->execute($sql);
	}
	
	private function setRegions($prefix){
		$sql = file_get_contents(__DIR__.'/../../install/data/regions.sql');
		$sql = str_replace(array('{{$prefix}}', '{{$time}}'), array($prefix, $this->current_time), $sql);
		$this->db->execute($sql);
	}
	
	private function setCats($prefix){
		$sql = file_get_contents(__DIR__.'/../../install/data/cats.sql');
		$sql = str_replace(array('{{$prefix}}', '{{$time}}'), array($prefix, $this->current_time), $sql);
		$this->db->execute($sql);
	}
	
	private function setMenus($prefix){
		$sql = file_get_contents(__DIR__.'/../../install/data/menus.sql');
		$sql = str_replace(array('{{$prefix}}', '{{$time}}'), array($prefix, $this->current_time), $sql);
		$this->db->execute($sql);
	}
	
	private function setActions($prefix){
		$sql = file_get_contents(__DIR__.'/../../install/data/actions.sql');
		$sql = str_replace(array('{{$prefix}}', '{{$time}}'), array($prefix, $this->current_time), $sql);
		$this->db->execute($sql);
	}
	
	private function setSystem($prefix){
		$sql = file_get_contents(__DIR__.'/../../install/data/system.sql');
		$sql = str_replace(array('{{$prefix}}', '{{$time}}'), array($prefix, $this->current_time), $sql);
		$this->db->execute($sql);
	}
	
	/**
	 * 对categories表进行索引
	 */
	private function indexCats(){
		Category::model()->db = $this->db;
		Categories::model()->db = $this->db;
		Category::model()->buildIndex();
	}
	
	/**
	 * 对menus表进行索引
	 */
	private function indexMenus(){
		Menu::model()->db = $this->db;
		Menus::model()->db = $this->db;
		Menu::model()->buildIndex();
	}
}