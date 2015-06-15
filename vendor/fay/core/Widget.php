<?php
namespace fay\core;

use fay\core\FBase;
use fay\helpers\Request;
use fay\models\tables\Widgets;

class Widget extends FBase{
	public $name;
	
	/**
	 * @var widget\View
	 */
	public $view;
	
	/**
	 * @var Input
	 */
	public $input;
	
	/**
	 * @var Session
	 */
	public $session;
	
	/**
	 * @var Flash
	 */
	public $flash;
	
	/**
	 * @var Cache
	 */
	public $cache;
	
	/**
	 * @var Config
	 */
	public $config;
	
	/**
	 * @var Db
	 */
	public $db;
	
	/**
	 * F::form('widget')
	 * @var Form
	 */
	public $form;
	
	/**
	 * 别名，并不一定存在
	 */
	public $alias;
	
	/**
	 * 当前时间
	 * @var int
	 */
	public $current_time = 0;
	
	public function __construct($options = array()){
		if(isset($options['name'])){
			//传入的name可能包含了前缀的路径
			$this->name = $options['name'];
		}
		
		include_once 'widget/View.php';
		$this->view = new widget\View($this->name, get_class($this));
		$this->input = Input::getInstance();
		$this->session = Session::getInstance();
		$this->cache = Cache::getInstance();
		$this->config = Config::getInstance();
		$this->flash = new Flash();
		$this->db = Db::getInstance();
		$this->form = $this->form('widget');
		
		$this->current_time = \F::app()->current_time;
		
		//当前用户登陆IP
		$this->ip = Request::getIP();
		
		$this->init();
	}
	
	/**
	 * Widget的构造函数不能被重写
	 * 如需初始化，重写init函数
	 */
	public function init(){}
	
	/**
	 * 存储该widget实例的参数，参数以数组的方式传入
	 */
	public function saveData($data){
		Widgets::model()->update(array(
			'options'=>json_encode($data),
		), $this->input->get('id', 'intval'));
	}
	
	/**
	 * 获取该widget实例的参数，参数以数组方式返回，若未设置参数，返回空数组
	 */
	public function getData(){
		$widget = Widgets::model()->find($this->input->get('id', 'intval'), 'options');
		if($widget['options']){
			return json_decode($widget['options'], true);
		}else{
			return array();
		}
	}
	
	/**
	 * 获取一个表单实例，若不指定name，返回后台小工具编辑表单。
	 * @param null|string $name 默认为后台小工具编辑表单
	 */
	public function form($name = 'widget'){
		return \F::form($name);
	}
	
	/**
	 * 表单验证规则
	 */
	public function rules(){
		return array();
	}
	
	/**
	 * 表单数据标签
	 */
	public function labels(){
		return array();
	}
	
	/**
	 * 表单获取数据时的过滤器
	 */
	public function filters(){
		return array();
	}
}