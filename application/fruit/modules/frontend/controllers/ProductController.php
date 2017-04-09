<?php
namespace fruit\modules\frontend\controllers;

use fruit\library\FrontController;
use cms\services\CategoryService;

class ProductController extends FrontController{
    public function __construct(){
        parent::__construct();
    
        $this->layout->current_header_menu = 'product';
    }
    
    public function index(){
        
        $this->view->render();
    }
    
    public function item(){
        $this->view->cats = CategoryService::service()->getChildren('product');
        
        $this->view->render();
    }
}