<?php
namespace jmc\modules\frontend\controllers;

use jmc\library\FrontendController;
use fay\models\Page;

class IndexController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
        
        $this->layout->title = '';
        $this->layout->keywords = '';
        $this->layout->description = '';
    }
    
    public function index()
    {
        $this->layout->page = 1;
        
        $this->view->about = Page::model()->getByAlias('about');
        
        $this->view->render();
    }
}