<?php
namespace milk\modules\frontend\controllers;

use milk\library\FrontendController;
use fay\models\Page;
use fay\models\Post;
class IndexController extends FrontendController
{   
    public function __construct()
    {
        parent::__construct();
    } 
    
    public function index()
    {
//         dump(Post::model()->getByCatId(10006));

        $this->view->about = Page::model()->getByAlias('about');
        
        $this->layout->section = 'index';
        
        $this->view->render();
    }
}