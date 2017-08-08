<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\AnalystSitesTable;
use cms\models\tables\AnalystVisitsTable;
use fay\common\ListView;
use fay\core\Loader;
use fay\core\Sql;
use fay\helpers\DateHelper;
use fay\helpers\IPHelper;

class AnalystController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'analyst';
    }
    
    public function visitor(){
        $this->layout->subtitle = '访客统计';
        
        //页面设置
        $this->settingForm('admin_analyst_visitor', '_setting_visitor', array(
            'cols'=>array('area', 'url', 'create_time', 'browser', 'shell', 'os', 'refer'),
            'page_size'=>20,
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
                'ip_int = ?'=>$this->input->get('ip', 'trim|RequestHelper::ip2int'),
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
        $this->view->sites = AnalystSitesTable::model()->fetchAll(array(
            'delete_time = 0',
        ), 'id,title');
        
        //引入IP地址库
        Loader::vendor('IpLocation/IpLocation.class');
        $this->view->iplocation = new \IpLocation();
        
        return $this->view->render();
    }
    
    public function views(){
        $this->layout->subtitle = '访问日志';
        
        //页面设置
        $this->settingForm('admin_analyst_views', '_setting_views', array(
            'cols'=>array('area', 'url', 'create_time', 'site', 'trackid', 'refer'),
            'page_size'=>20,
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
                'ip_int = ?'=>IPHelper::ip2int($this->input->get('ip', 'trim')),
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
        $this->view->sites = AnalystSitesTable::model()->fetchAll(array(
            'delete_time = 0',
        ), 'id,title');
        
        //引入IP地址库
        Loader::vendor('IpLocation/IpLocation.class');
        $this->view->iplocation = new \IpLocation();
        
        return $this->view->render();
    }
    
    public function pv(){
        //搜索条件验证，异常数据直接返回404
        $this->form('search')->setScene('final')->setRules(array(
            array(array('start_time', 'end_time'), 'datetime'),
            array('orderby', 'range', array(
                'range'=>AnalystVisitsTable::model()->getFields(),
            )),
            array('order', 'range', array(
                'range'=>array('asc', 'desc'),
            )),
            array('site', 'int', array('min'=>1))
        ))->check();
        
        $this->layout->subtitle = '页面PV量';
        
        $sql = new Sql();
        $sql->from(array('v'=>'analyst_visits'), 'url,SUM(views) AS pv,COUNT(DISTINCT mac) AS uv,COUNT(DISTINCT ip_int) AS ip')
            ->joinLeft(array('s'=>'analyst_sites'), 'v.site = s.id', 'title AS site_title')
            ->group(array('url', 'v.site'))
            ->countBy('DISTINCT url')
        ;
        
        $this->view->today = DateHelper::today();
        $this->view->yesterday = DateHelper::yesterday();
        $this->view->week = DateHelper::daysbefore(6);
        $this->view->month = DateHelper::daysbefore(29);
        
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
                'ip_int = ?'=>$this->input->get('ip', 'trim|RequestHelper::ip2int'),
            ));
        }
        if($this->input->get('site')){
            $sql->where(array(
                'site = ?'=>$this->input->get('site', 'intval'),
            ));
        }
        
        if($this->input->get('orderby')){
            $this->view->orderby = $this->input->get('orderby');
            $this->view->order = $this->input->get('order') == 'asc' ? 'ASC' : 'DESC';
            $sql->order("{$this->view->orderby} {$this->view->order}");
        }else{
            $sql->order('MAX(v.id) DESC');
        }

        $this->view->listview = new ListView($sql, array(
            'page_size'=>!empty($this->view->_settings['page_size']) ? $this->view->_settings['page_size'] : 20,
            'item_view'=>'_pv_list_item',
            'empty_text'=>'<tr><td colspan="5" align="center">无相关记录！</td></tr>',
        ));
        
        //所有站点
        $this->view->sites = AnalystSitesTable::model()->fetchAll(array(
            'delete_time = 0',
        ), 'id,title');
        
        //引入IP地址库
        Loader::vendor('IpLocation/IpLocation.class');
        $this->view->iplocation = new \IpLocation();
        
        return $this->view->render();
    }
    
    public function spiderlog(){
        $this->layout->subtitle = '蜘蛛爬行记录';
        
        //页面设置
        $this->settingForm('admin_analyst_spiderlog', '_setting_spiderlog', array(
            'page_size'=>30,
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
            'empty_text'=>'<tr><td colspan="5" align="center">无相关记录！</td></tr>',
        ));
        
        //引入IP地址库
        Loader::vendor('IpLocation/IpLocation.class');
        $this->view->iplocation = new \IpLocation();
        
        return $this->view->render();
    }
}