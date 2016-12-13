<?php
namespace fay\widget;

use fay\core\Config;
use fay\core\Db;
use fay\core\Input;
use fay\helpers\Request;
use fay\models\tables\Widgets;

abstract class Widget{
	/**
	 * 小工具名称（初始化时传入）
	 */
	public $name;
	
	/**
	 * 小工具在项目中的路径（初始化时传入，带末尾斜杠）
	 */
	public $path;
	
	/**
	 * 小工具标题（给用户看的描述），取README.md文件第一行作为标题
	 */
	public $title = '未命名小工具';
	
	/**
	 * 小工具描述（给用户看的描述），取README.md文件第二行作为描述
	 */
	public $description = '暂无描述';
	
	/**
	 * @var View
	 */
	public $view;
	
	/**
	 * @var \fay\core\Input
	 */
	public $input;
	
	/**
	 * F::form('widget')
	 * @var \fay\core\Form
	 */
	public $form;
	
	/**
	 * 别名，并不一定存在
	 */
	public $alias;
	
	/**
	 * 若在小工具域中调用，则此参数为本实例在小工具域中出现的位置。
	 * 否则为null
	 */
	public $_index;
	
	/**
	 * 配置信息
	 */
	public $config = array();
	
	/**
	 * 当前时间
	 * @var int
	 */
	public $current_time = 0;
	
	public function __construct($name, $path){
		//传入的name可能包含了前缀的路径
		$this->name = $name;
		
		//widget在项目中的路径，获取README.md文件的时候方便点
		$this->path = $path;
		
		if(strpos(get_class($this), 'controllers\AdminController') && file_exists($path . 'README.md')){
			//后台调用的时候，会从README.md文件获取标题和描述信息
			$readme = file($path . 'README.md');
			$this->title = trim($readme[0], " \t\n\r\0\x0B#");
			$this->description = trim($readme[1]);
		}
		
		include_once 'View.php';
		$this->view = new View($this->name);
		//将Controller实例传递给view
		$this->view->assign(array('widget'=>$this));
		$this->input = Input::getInstance();
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
	 * @param $data
	 */
	public function saveConfig($data){
		Widgets::model()->update(array(
			'options'=>json_encode($data),
		), "alias = '{$this->alias}'");
	}
	
	/**
	 * 获取该widget实例的参数，参数以数组方式返回，若未设置参数，返回空数组
	 */
	public function getConfig(){
		if($this->config){
			return $this->config;
		}
		
		$widget = Widgets::model()->fetchRow("alias = '{$this->alias}'", 'options');
		if(isset($widget['options']) && $widget['options']){
			return json_decode($widget['options'], true);
		}else{
			return array();
		}
	}
	
	/**
	 * 获取一个表单实例，若不指定name，返回后台小工具编辑表单。
	 * @param null|string $name 默认为后台小工具编辑表单
	 * @return \fay\core\Form
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
	
	/**
	 * 初始化配置信息
	 * @param $config
	 * @return array
	 */
	public function initConfig($config){
		return $this->config = $config ? $config : array();
	}
	
	/**
	 * 子类中实现，调用widget时自动执行此方法
	 * @param array $config
	 * @return
	 */
	abstract public function index();
}