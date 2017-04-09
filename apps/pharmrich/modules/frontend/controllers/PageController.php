<?php
namespace pharmrich\modules\frontend\controllers;

use pharmrich\library\FrontController;

class PageController extends FrontController{
    public function item(){
        
        $this->view->render();
    }
}