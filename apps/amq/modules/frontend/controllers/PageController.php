<?php
namespace amq\modules\frontend\controllers;

use amq\library\FrontController;
use cms\services\PageService;
use fay\core\HttpException;

class PageController extends FrontController{
    public function __construct(){
        parent::__construct();
    }
    
    public function item(){
        //表单验证
        $this->form()->setRules(array(
            array(array('page'), 'required'),
        ))->setFilters(array(
            'page'=>'trim',
        ))->setLabels(array(
            'page'=>'页面标识',
        ))->check();
        
        $page = PageService::service()->get($this->form()->getData('page'));
        if(!$page){
            throw new HttpException('您访问的页面不存在');
        }
        
        $this->view->assign(array(
            'page'=>$page
        ))->render();
    }
}