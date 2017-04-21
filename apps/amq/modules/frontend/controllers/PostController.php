<?php
namespace amq\modules\frontend\controllers;

use amq\library\FrontController;

class PostController extends FrontController{
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        $this->view->render();
    }
    
    public function item(){
        $this->view->render();
    }
}