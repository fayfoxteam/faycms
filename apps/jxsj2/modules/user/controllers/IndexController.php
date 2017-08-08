<?php
namespace jxsj2\modules\user\controllers;

use jxsj2\library\UserController;

class IndexController extends UserController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->current_directory = 'home';
    }
    
    public function index(){
        
        
        return $this->view->render();
    }
    
}