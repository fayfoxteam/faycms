<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\Setting;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\AnalystSites;
use fay\helpers\Date;
use fay\helpers\Request;
use fay\core\Loader;

class AnalystController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'analyst';
	}
	
	public function visitor(){
		$this->layout->subtitle = '访客统计';
		
		//自定义参数
		$this->layout->_setting_panel = '_setting_visitor';
		$_setting_key = 'admin_analyst_visitor';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array(
			'cols'=>array('area', 'url', 'create_time', 'browser', 'shell', 'os', 'refer'),
			'page_size'=>20,
		);
		$this->form('setting')->setModel(Setting::model())
			->setJsModel('setting')
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key,
			));
		
		$sql = new Sql();
		$sql->from(array('m'=>'analyst_macs'))
			->joinLeft(array('s'=>'analyst_sites'), 'm.site = s.id', 'title AS site_title')
			->order('m.id DESC');
		
		if($this->input->get('start_time')){
			$sql->where(array(
				'create_time > ?'=>$this->input->get('start_time', 'strtotime'),
			));
		}
		if($this->input->get('end_time')){
			$sql->where(array(
				'create_time < ?'=>$this->input->get('end_time', 'strtotime'),
			));
		}
		if($this->input->get('trackid')){
			$sql->where(array(
				'trackid LIKE ?'=>$this->input->get('trackid').'%',
			));
		}
		if($this->input->get('ip')){
			$sql->where(array(
				'ip_int = ?'=>$this->input->get('ip', 'trim|Request::ip2int'),
			));
		}
		if($this->input->get('site')){
			$sql->where(array(
				'site = ?'=>$this->input->get('site', 'intval'),
			));
		}
		if($this->input->get('se')){
			$sql->where(array(
				'se = ?'=>$this->input->get('se'),
			));
		}
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>$this->form('setting')->getData('page_size', 20),
			'item_view'=>'_visit_list_item',
			'empty_text'=>'<tr><td colspan="'.count($this->form('setting')->getData('cols')).'" align="center">无相关记录！</td></tr>',
		));
		
		//所有站点
		$this->view->sites = AnalystSites::model()->fetchAll(array(
			'deleted = 0',
		), 'id,title');
		
		//引入IP地址库
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		$this->view->render();
	}
	
	public function views(){
		$this->layout->subtitle = '访问日志';
		
		//自定义参数
		$this->layout->_setting_panel = '_setting_views';
		$_setting_key = 'admin_analyst_views';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array(
			'cols'=>array('area', 'url', 'create_time', 'site', 'trackid', 'refer'),
			'page_size'=>20,
		);
		$this->form('setting')->setModel(Setting::model())
			->setJsModel('setting')
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key,
			));
		
		$sql = new Sql();
		$sql->from(array('v'=>'analyst_visits'))
			->joinLeft(array('s'=>'analyst_sites'), 'v.site = s.id', 'title AS site_title')
			->order('v.id DESC');
		
		if($this->input->get('start_time')){
			$sql->where(array(
				'create_time > ?'=>$this->input->get('start_time', 'strtotime'),
			));
		}
		if($this->input->get('end_time')){
			$sql->where(array(
				'create_time < ?'=>$this->input->get('end_time', 'strtotime'),
			));
		}
		if($this->input->get('trackid')){
			$sql->where(array(
				'trackid LIKE ?'=>$this->input->get('trackid').'%',
			));
		}
		if($this->input->get('ip')){
			$sql->where(array(
				'ip_int = ?'=>Request::ip2int($this->input->get('ip', 'trim')),
			));
		}
		if($this->input->get('site')){
			$sql->where(array(
				'site = ?'=>$this->input->get('site', 'intval'),
			));
		}
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>!empty($this->view->_settings['page_size']) ? $this->view->_settings['page_size'] : 20,
			'item_view'=>'_views_list_item',
			'empty_text'=>'<tr><td colspan="'.count($this->form('setting')->getData('cols')).'" align="center">无相关记录！</td></tr>',
		));

		//所有站点
		$this->view->sites = AnalystSites::model()->fetchAll(array(
			'deleted = 0',
		), 'id,title');
		
		//引入IP地址库
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		$this->view->render();
	}
	
	public function pv(){
		$this->layout->subtitle = '页面PV量';
		
		$sql = new Sql();
		$sql->from(array('v'=>'analyst_visits'), 'mac,url,SUM(views) AS pv,COUNT(DISTINCT mac) AS uv,COUNT(DISTINCT ip_int) AS ip')
			->joinLeft(array('s'=>'analyst_sites'), 'v.site = s.id', 'title AS site_title')
			->group('short_url')
			->countBy('DISTINCT short_url')
		;
		
		$this->view->today = Date::today();
		$this->view->yesterday = Date::yesterday();
		$this->view->week = Date::daysbefore(6);
		$this->view->month = Date::daysbefore(29);
		
		if($this->input->get('start_time') || $this->input->get('end_time')){
			$start_time = $this->input->get('start_time', 'strtotime');
			$end_time = $this->input->get('end_time', 'strtotime');
		}else{
			//默认值
			$start_time = $this->view->today;
			$this->form('search')->setData(array(
				'start_time'=>date('Y-m-d 00:00:00', $this->view->today)
			));
			$end_time = '';
		}
		
		if($start_time == $this->view->today && $end_time == ''){
			$this->view->flag = 'today';
		}else if($start_time == $this->view->yesterday && $end_time == $this->view->today){
			$this->view->flag = 'yesterday';
		}else if($start_time == $this->view->week && $end_time == ''){
			$this->view->flag = 'week';
		}else if($start_time == $this->view->month && $end_time == ''){
			$this->view->flag = 'month';
		}
		
		if($start_time){
			$sql->where(array(
				'create_time > ?'=>$start_time,
			));
		}
		if($end_time){
			$sql->where(array(
				'create_time < ?'=>$end_time,
			));
		}
		if($this->input->get('trackid')){
			$sql->where(array(
				'trackid LIKE ?'=>$this->input->get('trackid').'%',
			));
		}
		if($this->input->get('ip')){
			$sql->where(array(
				'ip_int = ?'=>$this->input->get('ip', 'trim|Request::ip2int'),
			));
		}
		if($this->input->get('site')){
			$sql->where(array(
				'site = ?'=>$this->input->get('site', 'intval'),
			));
		}
		
		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'asc' : 'desc';
			$sql->order("{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('v.id DESC');
		}

		$this->view->listview = new ListView($sql, array(
			'page_size'=>!empty($this->view->_settings['page_size']) ? $this->view->_settings['page_size'] : 20,
			'item_view'=>'_pv_list_item',
			'empty_text'=>'<tr><td colspan="5" align="center">无相关记录！</td></tr>',
		));
		
		//所有站点
		$this->view->sites = AnalystSites::model()->fetchAll(array(
			'deleted = 0',
		), 'id,title');
		
		//引入IP地址库
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		$this->view->render();
	}
	
	public function spiderlog(){
		$this->layout->subtitle = '蜘蛛爬行记录';
		
		//自定义参数
		$this->layout->_setting_panel = '_setting_spiderlog';
		$_setting_key = 'admin_analyst_spiderlog';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array(
			'page_size'=>30,
		);
		$this->form('setting')->setModel(Setting::model())
			->setJsModel('setting')
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key,
			));
		

		$sql = new Sql();
		$sql->from('spider_logs')
			->order('id DESC');
		
		if($this->input->get('start_time')){
			$sql->where(array(
				'create_time >= ?'=>$this->input->get('start_time', 'strtotime'),
			));
		}
		if($this->input->get('end_time')){
			$sql->where(array(
				'create_time <= ?'=>$this->input->get('end_time', 'strtotime'),
			));
		}
		
		//url
		if($this->input->get('url')){
			$sql->where(array(
				'url = ?'=>$this->input->get('url', 'trim'),
			));
		}
		
		//搜索引擎
		if($this->input->get('spider')){
			$sql->where(array(
				'spider LIKE ?'=>$this->input->get('spider').'%',
			));
		}
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>$this->form('setting')->getData('page_size', 30),
			'item_view'=>'_spiderlog_list_item',
		));
		
		//引入IP地址库
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		$this->view->render();
	}
}