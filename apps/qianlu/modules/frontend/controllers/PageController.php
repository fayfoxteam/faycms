<?php
namespace qianlu\modules\frontend\controllers;

use fay\exceptions\NotFoundHttpException;
use qianlu\library\FrontController;
use cms\models\tables\PagesTable;

class PageController extends FrontController{
    public $layout_template = 'inner';
    
    public function __construct(){
        parent::__construct();
    }
    
    public function item(){
        if($this->input->get('alias')){
            $page = PagesTable::model()->fetchRow(array('alias = ?'=>$this->input->get('alias')));
        }else if($this->input->get('id')){
            $page = PagesTable::model()->fetchRow(array('id = ?'=>$this->input->get('id', 'intval')));
        }
        
        if(isset($page) && $page){
            PagesTable::model()->incr($page['id'], 'views', 1);
            $this->view->page = $page;
        }else{
            throw new NotFoundHttpException('您请求的页面不存在');
        }

        $this->layout->submenu = array(
            array(
                'title'=>'关于有道',
                'link'=>'',
                'class'=>'sel',
            ),
            array(
                'title'=>'公司动态',
                'link'=>'',
                'class'=>'',
            ),
            array(
                'title'=>'项目动态',
                'link'=>'',
                'class'=>'',
            ),
        );
        $this->layout->subtitle = '公司概况';
        $this->layout->breadcrumbs = array(
            array(
                'title'=>'首页',
                'link'=>$this->view->url(),
            ),
            array(
                'title'=>'关于有道',
                'link'=>$this->view->url('about'),
            ),
            array(
                'title'=>'企业简介',
            ),
        );
        $this->layout->banner = $page['alias'].'-banner.jpg';
        $this->layout->current_directory = $page['alias'];
        return $this->view->render();
    }
}