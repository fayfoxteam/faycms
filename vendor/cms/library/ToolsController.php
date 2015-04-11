<?php
namespace cms\library;

use fay\core\Controller;
use fay\core\Uri;
use fay\models\tables\Users;
use fay\models\File;
use fay\core\Response;
use fay\core\HttpException;

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
			'role'=>Users::ROLE_SUPERADMIN,
		),
	);
	
	public function __construct(){
		parent::__construct();
		//重置session_namespace
		$this->config->set('session_namespace', $this->config->get('session_namespace').'_admin');
		
		$this->layout->current_directory = '';
		$this->layout->subtitle = '';
	}
	
	public function isLogin(){
		//验证session中是否有值
		if(!$this->session->get('username')){
			Response::redirect('admin/login/index', array('redirect'=>base64_encode($this->view->url(Uri::getInstance()->router, $this->input->get()))));
		}
		if($this->session->get('role') != Users::ROLE_SUPERADMIN){
			throw new HttpException('仅超级管理员可访问此模块', 403);
		}
		//设置当前用户id
		$this->current_user = $this->session->get('id');
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