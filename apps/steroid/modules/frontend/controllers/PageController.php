<?php
namespace steroid\modules\frontend\controllers;

use steroid\library\FrontController;
use fay\core\HttpException;
use cms\services\CategoryService;

class PageController extends FrontController{
    public $layout_template = 'post';
    
    public function item(){
        $this->view->render();
    }
}