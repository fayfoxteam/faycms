<?php
namespace siwi\modules\user\controllers;

use siwi\library\UserController;

class IndexController extends UserController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->title = '';
        $this->layout->keywords = '';
        $this->layout->description = '';
        
        $this->layout->current_directory = 'home';
    }
    
    public function index(){
        
        
        $this->view->render();
    }
    
}