<?php
namespace blog\modules\frontend\controllers;

use blog\library\FrontController;
use fay\models\tables\PostsTable;
use fay\services\CategoryService;

class SitemapController extends FrontController{
    public function xml(){
        $this->layout_template = false;
        header('Content-type: text/xml');
        $this->view->posts = PostsTable::model()->fetchAll(
            PostsTable::getPublishedConditions('p'),
            'id,title,publish_time',
            'publish_time DESC'
        );
        
        $this->view->cats = CategoryService::service()->getNextLevel('_system_post');
        $this->view->render();
    }
    
    public function html(){
        
    }
}