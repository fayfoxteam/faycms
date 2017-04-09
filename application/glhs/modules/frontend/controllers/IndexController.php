<?php
namespace glhs\modules\frontend\controllers;

use glhs\library\FrontController;
use fay\services\OptionService;

class IndexController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->title = '首页';
        $this->layout->keywords = OptionService::get('site:seo_index_keywords');
        $this->layout->description = OptionService::get('site:seo_index_description');
        
        $this->layout->current_directory = 'home';
    }
    
    public function index(){
        $this->view->render();
    }
    
}