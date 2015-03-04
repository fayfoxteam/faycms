<?php
namespace tzcs\modules\frontend\controllers;

use tzcs\library\FrontController;
use fay\models\Page;
class IndexController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->title = '首页';
        $this->layout->keywords = '';
    	$this->layout->description = '';
    }
    
    public function index(){
//         $this->view->about = Page::model()->getByAlias('about');//关于
     
       
        $this->view->render();
    }
}