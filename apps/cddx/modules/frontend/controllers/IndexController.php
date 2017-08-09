<?php
namespace cddx\modules\frontend\controllers;

use cddx\library\FrontController;
use cms\services\PageService;
use cms\services\CategoryService;
use cms\services\post\PostCategoryService;
use cms\services\post\PostService;

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
        $cat_news = CategoryService::service()->get('news');
        $news = PostCategoryService::service()->getPosts($cat_news, 6, 'id,title,abstract,publish_time', true);
        
        return $this->view->assign(array(
            'about'=>$page_about,
            'cat_news'=>$cat_news,
            'news'=>$news,
        ))->render();
    }
    
}