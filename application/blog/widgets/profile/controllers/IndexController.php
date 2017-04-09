<?php
namespace blog\widgets\profile\controllers;

use fay\widget\Widget;

class IndexController extends Widget{
    
    public function index(){
        $this->view->render();
    }
    
}