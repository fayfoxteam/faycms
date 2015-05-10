<?php
namespace hq\modules\frontend\controllers;

use hq\library\FrontController;

class IndexController extends FrontController
{
    public function index()
    {
        $this->view->render();
    }
}