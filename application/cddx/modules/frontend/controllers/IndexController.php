<?php
namespace cddx\modules\frontend\controllers;

use cddx\library\FrontController;
use fay\services\PageService;
use fay\services\CategoryService;
use fay\services\post\PostCategoryService;
use fay\services\post\PostService;

class IndexController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->title = '';
        $this->layout->keywords = '';
        $this->layout->description = '';
        
        $this->layout->current_header_menu = 'home';
    }
    
    public function index(){
        $page_about = PageService::service()->getByAlias('about');
        $cat_news = CategoryService::service()->getByAlias('news');
        $news = PostCategoryService::service()->getPosts($cat_news, 6, 'id,title,abstract,publish_time', true);
        
        $this->view->assign(array(
            'about'=>$page_about,
            'cat_news'=>$cat_news,
            'news'=>$news,
        ))->render();
    }
    
}