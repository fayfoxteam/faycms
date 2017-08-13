<?php
namespace jxsj2\modules\frontend\controllers;

use fay\core\exceptions\NotFoundHttpException;
use jxsj2\library\FrontController;
use cms\services\PageService;
use cms\models\tables\PagesTable;

class PageController extends FrontController{
    public function item(){
        $page = PageService::service()->get($this->input->get('id', 'intval'));
        
        if(!$page){
            throw new NotFoundHttpException('页面不存在');
        }
        //阅读数
        PagesTable::model()->incr($page['id'], 'views', 1);
        
        //seo
        $this->layout->title = $page['seo_title'];
        $this->layout->keywords = $page['seo_keywords'];
        $this->layout->description = $page['seo_description'];
        
        $this->view->page = $page;
        return $this->view->render();
    }
    
}









