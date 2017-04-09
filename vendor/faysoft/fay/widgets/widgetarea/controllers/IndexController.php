<?php
namespace fay\widgets\widgetarea\controllers;

use fay\widget\Widget;

class IndexController extends Widget{
    public function getData(){
        
    }
    
    public function index(){
        if(!empty($this->config['alias'])){
            $this->renderTemplate();
        }
    }
}