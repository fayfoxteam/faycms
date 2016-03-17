<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\Setting;

class IndexController extends AdminController{
	//首页的boxes，本质上是widget
	public $boxes = array(
		array('name'=>'cms/change_app', 'title'=>'平台切换'),
		array('name'=>'cms/tongji_chart', 'title'=>'访问统计（图表）'),
		array('name'=>'cms/tongji', 'title'=>'访问统计（概况）'),
		array('name'=>'cms/ip_statistics', 'title'=>'IP统计'),
		array('name'=>'cms/user_info', 'title'=>'用户信息'),
		array('name'=>'cms/check_system', 'title'=>'系统检测'),
		array('name'=>'cms/admins_online', 'title'=>'在线管理员'),
		array('name'=>'cms/feeds', 'title'=>'Feeds'),
	);
	public $ajax_boxes = array('cms/ip_statistics', 'cms/user_info');
	
	//不写构造函数的话，index方法会被认为是构造函数
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->layout->subtitle = '控制台';
		//要显示的box
		$this->layout->_setting_panel = '_setting_index';
		$_setting_key = 'admin_dashboard_boxes';
		$this->form('setting')->setModel(Setting::model())
			->setJsModel('setting')
			->setData(array(
				'_key'=>$_setting_key,
			));
		$this->view->enabled_boxes = $this->getEnabledBoxes($_setting_key);
		
		//box排序
		$this->view->_settings = Setting::model()->get('admin_dashboard_box_sort');
		
		if($this->view->_settings === null){
			$this->view->_settings = array();
			$half = intval(count($this->view->enabled_boxes) / 2);
			$this->view->_settings['dashboard-left'] = array_slice($this->view->enabled_boxes, 0, $half);
			$this->view->_settings['dashboard-right'] = array_slice($this->view->enabled_boxes, $half);
		}
		
		$this->view->render('index');
	}
}