<?php
namespace vote\modules\frontend\controllers;

use vote\library\FrontendController;
use fay\models\Post;
use fay\models\Page;
class IndexController extends FrontendController
{
    public function index()
    {
        $this->view->introduce = Page::model()->getByAlias('introduce');
        
        $this->view->lists = Post::model()->getByCatAlias('list');
        
        $this->view->render();
    }
}