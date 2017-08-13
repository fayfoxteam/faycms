<?php
namespace steroid\modules\frontend\controllers;

use steroid\library\FrontController;

class PageController extends FrontController{
    public $layout_template = 'post';
    
    public function item(){
        return $this->view->render();
    }
}