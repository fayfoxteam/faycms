<?php
namespace shinecolor\modules\frontend\controllers;

use shinecolor\library\FrontController;
use fay\services\OptionService;
use fay\services\post\PostService;
use fay\services\CategoryService;
use cms\models\tables\PostsTable;

class IndexController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->title = '';
        $this->layout->keywords = '';
        $this->layout->description = '';
        
        $this->layout->current_header_menu = 'home';
    }
    
    public function index(){
        $this->layout->keywords = OptionService::get('site:seo_index_keywords');
        $this->layout->description = OptionService::get('site:seo_index_description');
        
        $this->view->news = \fay\services\post\CategoryService::service()->getPosts('news', 7, 'id,title,publish_time', true);
        
        $cat_product = CategoryService::service()->getByAlias('product');
        $this->view->products = PostsTable::model()->fetchAll(array(
            'cat_id = '.$cat_product['id'],
        ), 'id,title,thumbnail', 'is_top DESC, sort, publish_time DESC');
        
        $this->view->render();
    }
    
}