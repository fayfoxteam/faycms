<?php
namespace shinecolor\modules\frontend\controllers;

use shinecolor\library\FrontController;
use cms\services\OptionService;
use cms\services\CategoryService;
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
        
        $this->view->news = \cms\services\post\PostCategoryService::service()->getPosts('news', 7, 'id,title,publish_time', true);
        
        $cat_product = CategoryService::service()->get('product');
        $this->view->products = PostsTable::model()->fetchAll(array(
            'cat_id = '.$cat_product['id'],
        ), 'id,title,thumbnail', 'is_top DESC, sort DESC, publish_time DESC');
        
        $this->view->render();
    }
    
}