<?php
namespace cms\library;

use fay\core\Controller;
use fay\core\Uri;
use fay\models\File;
use fay\core\Response;
use fay\core\HttpException;
use fay\models\tables\Roles;
use fay\models\User;
use fay\models\user\Role;

class ToolsController extends Controller{
	public $layout_template = 'admin';
	/**
	 * 当前用户id（users表中的ID）
	 * @var int
	 */
	public $current_user = 0;
	
	public $_top_nav = array(
		array(
			'label'=>'站点首页',
			'icon'=>'fa fa-home',
			'router'=>null,
			'target'=>'_blank',
		),
		array(
			'label'=>'控制台',
			'icon'=>'fa fa-dashboard',
			'router'=>'admin/index/index',
		),
		array(
			'label'=>'Tools',
			'icon'=>'fa fa-wrench',
			'router'=>'tools',
			'roles'=>Roles::ITEM_SUPER_ADMIN,
		),
	);
	
	public function __construct(){
		parent::__construct();
		//重置session_namespace
		$this->config->set('session_namespace', $this->config->get('session_namespace').'_admin');
		
		$this->layout->current_directory = '';
		$this->layout->subtitle = '';
	}
	
	/**
	 * 验证仅超级管理员可访问
	 * @throws \fay\core\HttpException
	 */
	public function isLogin(){
		//验证session中是否有值
		if(!User::model()->isAdmin()){
			Response::redirect('admin/login/index', array('redirect'=>base64_encode($this->view->url(Uri::getInstance()->router, $this->input->get()))));
		}
		if(Role::model()->is(Roles::ITEM_SUPER_ADMIN)){
			throw new HttpException('仅超级管理员可访问此模块', 403);
		}
		
		//设置当前用户id
		$this->current_user = \F::session()->get('user.id');
	}
	
	public function getApps(){
		$app_dirs = File::getFileList(APPLICATION_PATH.'..');
		$apps = array();
		foreach($app_dirs as $app){
			$apps[] = $app['name'];
		}
		return $apps;
	}
}