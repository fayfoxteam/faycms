<?php
namespace amq\modules\frontend\controllers;

use amq\library\FrontController;

class PostController extends FrontController{
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        return $this->view->render();
    }
    
    public function item(){
        return $this->view->render();
    }
}