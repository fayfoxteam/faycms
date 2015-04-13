<?php
namespace fay\core;

use fay\helpers\RequestHelper;
use fay\models\tables\Actions;
use fay\models\tables\Users;

class Controller{
	/**
	 * 检查过被阻止的路由
	 */
	protected $_deny_routers = array();
	
	/**
	 * @var Uri
	 */
	public $uri;
	/**
	 * @var Input
	 */
	public $input;
	/**
	 * @var Config
	 */
	public $config;
	/**
	 * @var View
	 */
	public $view;
	/**
	 * @var Cache
	 */
	public $cache;
	/**
	 * @var Session
	 */
	public $session;
	/**
	 * @var Flash
	 */
	public $flash;
	/**
	 * @var FWidget
	 */
	public $widget;
	/**
	 * @var Layout
	 */
	public $layout;
	/**
	 * 模板文件
	 * @var string
	 */
	public $layout_template;
	/**
	 * 当前时间
	 * @var int
	 */
	public $current_time = 0;
	/**
	 * @var Controller
	 */
	private static $_instance;
	/**
	 * 当前用户登陆IP
	 * @var string
	 */
	public $ip = '';
	
	public function __construct(){
		$this->input = Input::getInstance();
		$this->view = new View();
		$this->layout = new Layout();
		$this->session = Session::getInstance();
		$this->cache = new Cache();
		$this->flash = new Flash();
		$this->config = Config::getInstance();
		$this->current_time = time();
		$this->uri = Uri::getInstance();
		$this->widget = new FWidget();
		self::$_instance = $this;
		
		//当前用户登陆IP
		$this->ip = RequestHelper::getIP();
	}
	
	public static function getInstance(){
		return self::$_instance;
	}
	
	/**
	 * 获取一个表单实例，若name为null，返回第一个被实例化的表单。
	 * 	若没有表单被实例化，实例化一个default
	 * @param null|string $name 默认为第一个被实例化的表单
	 */
	public function form($name = 'default'){
		return \F::form($name);
	}

	/**
	 * 根据路由做权限检查
	 * @param string $router
	 * @return boolean
	 */
	public function checkPermission($router){
		if($this->session->get('role') == Users::ROLE_SUPERADMIN){
			//超级管理员无限制
			return true;
		}else if(in_array($router, $this->session->get('actions', array()))){
			//用户有此权限
			return true;
		}else{
			if(in_array($router, $this->_deny_routers)){
				//已经检查过此路由为不可访问路由
				return false;
			}
			$action = Actions::model()->fetchRow(array('router = ?'=>$router), 'is_public');
			if($action['is_public']){
				//此路由为公共路由
				return true;
			}else if(!$action){
				//此路由并不在权限路由列表内，视为公共路由
				return true;
			}
		}
		$this->_deny_routers[] = $router;
		return false;
	}
}