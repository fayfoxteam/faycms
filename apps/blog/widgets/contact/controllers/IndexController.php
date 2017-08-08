<?php
namespace blog\widgets\contact\controllers;

use fay\widget\Widget;

class IndexController extends Widget{
    
    public function index(){
        return $this->view->render();
    }
    
}