<?php
namespace cddx2\modules\frontend\controllers;

use cddx2\library\FrontController;
use fay\core\exceptions\NotFoundHttpException;
use cms\services\CategoryService;

class PostController extends FrontController{
    public function index(){
        $cat_id = $this->input->get('cat_id', 'intval');
        
        //获取分类
        if(!$cat_id || !$cat = CategoryService::service()->get($cat_id)){
            throw new NotFoundHttpException('您请求的页面不存在');
        }
        
        $this->view->cat = $cat;
        
        return $this->view->render();
    }
    
    public function item(){
        return $this->view->render();
    }
}