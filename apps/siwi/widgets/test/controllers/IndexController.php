<?php
namespace siwi\widgets\test\controllers;

use fay\widget\Widget;

class IndexController extends Widget{
    public function index($options){
        return $this->view->render();
    }
}