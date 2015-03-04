<?php
namespace fay\core;

use fay\helpers\RequestHelper;

class Controller{
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
	
}