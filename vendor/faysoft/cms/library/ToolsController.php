<?php
namespace cms\library;

use fay\core\Controller;
use fay\core\Uri;
use fay\services\file\FileService;
use fay\core\Response;
use fay\core\HttpException;
use fay\models\tables\RolesTable;
use fay\services\user\UserService;
use fay\services\user\UserRoleService;

class ToolsController extends Controller{
	public $layout_template = 'admin';
	
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
			'router'=>'cms/admin/index/index',
		),
		array(
			'label'=>'Tools',
			'icon'=>'fa fa-wrench',
			'router'=>'tools',
			'roles'=>RolesTable::ITEM_SUPER_ADMIN,
		),
	);
	
	public function __construct(){
		if(!\F::config()->get('enable_tools')){
			throw new HttpException('系统禁用了Tools，可在configs/main.php中开启', 403);
		}
		parent::__construct();
		//重置session.namespace
		\F::config()->set('session.namespace', \F::config()->get('session.namespace').'_admin');
		
		$this->current_user = \F::session()->get('user.id', 0);
		
		$this->layout->current_directory = '';
		$this->layout->subtitle = '';
	}
	
	/**
	 * 验证仅超级管理员可访问
	 * @throws \fay\core\HttpException
	 */
	public function isLogin(){
		//设置当前用户id
		$this->current_user = \F::session()->get('user.id', 0);
		
		//验证session中是否有值
		if(!UserService::service()->isAdmin()){
			Response::redirect('cms/admin/login/index', array('redirect'=>base64_encode($this->view->url(Uri::getInstance()->router, $this->input->get()))));
		}
		
		if(!UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN)){
			throw new HttpException('仅超级管理员可访问此模块', 403);
		}
	}
	
	public function getApps(){
		$app_dirs = FileService::getFileList(APPLICATION_PATH.'..');
		$apps = array();
		foreach($app_dirs as $app){
			$apps[] = $app['name'];
		}
		return $apps;
	}
}