<?php
namespace cddx2\modules\frontend\controllers;

use cddx2\library\FrontController;
use fay\core\HttpException;
use cms\services\CategoryService;

class PostController extends FrontController{
    public function index(){
        $cat_id = $this->input->get('cat_id', 'intval');
        
        //获取分类
        if(!$cat_id || !$cat = CategoryService::service()->get($cat_id)){
            throw new HttpException('您请求的页面不存在');
        }
        
        $this->view->cat = $cat;
        
        $this->view->render();
    }
    
    public function item(){
        $this->view->render();
    }
}