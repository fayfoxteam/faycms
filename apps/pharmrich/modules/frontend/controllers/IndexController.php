<?php
namespace pharmrich\modules\frontend\controllers;

use pharmrich\library\FrontController;
use cms\services\OptionService;

class IndexController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->title = OptionService::get('site:seo_index_title');
        $this->layout->keywords = OptionService::get('site:seo_index_keywords');
        $this->layout->description = OptionService::get('site:seo_index_description');
        
        $this->layout->current_header_menu = 'home';
    }
    
    public function index(){
        return $this->view->render();
    }
    
}