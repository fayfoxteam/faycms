<?php
namespace vote\modules\frontend\controllers;

use vote\library\FrontendController;
class IndexController extends FrontendController
{
    public function index()
    {
        
        $this->view->render();
    }
}