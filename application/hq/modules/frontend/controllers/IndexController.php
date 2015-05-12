<?php
namespace hq\modules\frontend\controllers;

use hq\library\FrontController;

class IndexController extends FrontController
{
    public function index()
    {
        $this->session->set('tab', 'index');
        $this->view->render();
    }
}