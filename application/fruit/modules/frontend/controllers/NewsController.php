<?php
namespace fruit\modules\frontend\controllers;

use fruit\library\FrontController;

class NewsController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->current_header_menu = 'news';
    }
    
    public function index(){
        $this->view->render();
    }
    
    public function item(){
        $this->view->render();
    }
}