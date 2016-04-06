<?php
namespace apidoc\library;

use fay\core\Controller;
use fay\helpers\Request;
use fay\models\tables\SpiderLogs;
use fay\models\Menu;

class FrontController extends Controller{
	public $layout_template = 'frontend';
	
	public $_left_menu = array();
	
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
		),
	);
	
	public function __construct(){
		parent::__construct();
		
		//设置当前用户id
		$this->current_user = \F::session()->get('user.id', 0);
		
		if($spider = Request::isSpider()){//如果是蜘蛛，记录蜘蛛日志
			SpiderLogs::model()->insert(array(
				'spider'=>$spider,
				'url'=>'http://'.(isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI'],
				'user_agent'=>isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
				'ip_int'=>Request::ip2int($this->ip),
				'create_time'=>$this->current_time,
			));
		}
		
		$this->layout->current_directory = '';
		$this->layout->subtitle = '';
		
		$this->_left_menu = Menu::model()->getTree('_admin_main');
	}
}