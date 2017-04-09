<?php
namespace cms\widgets\js_info\controllers;

use fay\widget\Widget;

class IndexController extends Widget{
    public function index(){
        $this->view->render();
    }
}