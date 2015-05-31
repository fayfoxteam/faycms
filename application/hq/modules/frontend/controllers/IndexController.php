<?php
namespace hq\modules\frontend\controllers;

use hq\library\FrontController;

class IndexController extends FrontController
{
    public function index()
    {
        echo 'index';
//        $this->session->set('tab', 'index');
//        $this->view->render();
    }
}