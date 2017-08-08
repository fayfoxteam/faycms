<?php
namespace cddx2\modules\frontend\controllers;

use cddx2\library\FrontController;
use cms\services\OptionService;
use cms\services\MenuService;

class IndexController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->title = OptionService::get('site:seo_index_title');
        $this->layout->keywords = OptionService::get('site:seo_index_keywords');
        $this->layout->description = OptionService::get('site:seo_index_description');
        
        $this->layout->current_directory = 'home';
    }
    
    public function index(){
        $this->view->menus = MenuService::service()->getTree('_user_menu');
        
        return $this->view->render();
    }
    
}