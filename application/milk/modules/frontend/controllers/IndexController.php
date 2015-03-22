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
        
        $this->layout->title = '';
        $this->layout->description = '';
        $this->layout->keywords = '';
    } 
    
    public function index()
    {
//         dump(Post::model()->getByCatId(10006));
       
        $this->view->about = Page::model()->getByAlias('about');
        
        $this->view->render();
    }
}