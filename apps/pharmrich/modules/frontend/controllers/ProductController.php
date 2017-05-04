<?php
namespace pharmrich\modules\frontend\controllers;

use pharmrich\library\FrontController;
use fay\core\HttpException;
use cms\services\CategoryService;

class ProductController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->current_header_menu = 'products';
    }
    
    public function index(){
        $cat_alias = $this->input->get('cat_alias', 'trim');
        
        if($cat_alias){
            $cat = CategoryService::service()->get($cat_alias);
            if(!$cat){
                throw new HttpException('您请求的页面不存在');
            }
        }else{
            $cat = CategoryService::service()->get('products');
        }
        
        $this->view->cat = $cat;
        
        $this->view->render();
    }
    
    public function item(){
        $this->view->render();
    }
}