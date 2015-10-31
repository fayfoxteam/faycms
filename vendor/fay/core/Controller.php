<?php
namespace fay\core;

use fay\helpers\Request;
use fay\models\tables\Actions;
use fay\helpers\String;
use fay\models\tables\Roles;

class Controller{
	/**
	 * 检查过被阻止的路由
	 */
	protected $_deny_routers = array();
	
	/**
	 * 随机token，用于防止重复请求（并不一定用到）
	 */
	private $token;
	
	/**
	 * @var Uri
	 */
	public $uri;
	/**
	 * @var \fay\core\Input
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
	 * 当前时间时间戳
	 */
	public $current_time = 0;
	/**
	 * 当前登录用户ID
	 */
	public $current_user = 0;
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
		$this->cache = new Cache();
		$this->config = Config::getInstance();
		$this->current_time = time();
		$this->uri = Uri::getInstance();
		$this->widget = new FWidget();
		self::$_instance = $this;
		
		//当前用户登陆IP
		$this->ip = Request::getIP();
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
		if(in_array(Roles::ITEM_SUPER_ADMIN, \F::session()->get('user.roles', array()))){
			//超级管理员无限制
			return true;
		}else if(in_array($router, \F::session()->get('actions', array()))){
			//用户有此权限
			return true;
		}else{
			if(in_array($router, $this->_deny_routers)){
				//已经检查过此路由为不可访问路由
				return false;
			}
			$action = Actions::model()->fetchRow(array('router = ?'=>$router), 'is_public');
			//此路由并不在权限路由列表内，视为公共路由
			if(!$action || $action['is_public']){
				return true;
			}
		}
		$this->_deny_routers[] = $router;
		return false;
	}
	
	/**
	 * 生成一个token并返回。
	 * 一次http请求只会生成一个token。
	 */
	public function getToken(){
		if(!$this->token){
			//设置token
			$this->token = String::random();
			\F::session()->set('_token', $this->token);
		}
		return $this->token;
	}
	
	/**
	 * 检查token防重复提交，每次校验都会重新生成一个token
	 * @return boolean
	 */
	protected function checkToken(){
		$token = $this->input->request('_token');
		$last_token = \F::session()->get('_token');
		$this->getToken();
		if($token && $token == $last_token){
			return true;
		}else{
			throw new Exception('Token校验失败');
		}
	}
}