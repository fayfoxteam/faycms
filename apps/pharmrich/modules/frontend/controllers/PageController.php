<?php
namespace pharmrich\modules\frontend\controllers;

use pharmrich\library\FrontController;

class PageController extends FrontController{
    public function item(){
        
        return $this->view->render();
    }
}