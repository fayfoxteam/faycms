<?php
namespace milk\modules\frontend\controllers;

use milk\library\FrontendController;
use fay\models\Page;
class IndexController extends FrontendController
{    
    public function index()
    {
       
        $this->view->about = Page::model()->getByAlias('about');
        
        $this->view->render();
    }
}