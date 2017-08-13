<?php
namespace church\modules\frontend\controllers;

use church\library\FrontController;
use fay\core\exceptions\NotFoundHttpException;
use cms\services\CategoryService;

class CatController extends FrontController{
    public function item(){
        $cat = $this->input->get('cat', 'trim');
        if(!$cat || !$cat = CategoryService::service()->get($cat)){
            throw new NotFoundHttpException('您请求的页面不存在');
        }
        
        $this->layout->assign(array(
            'page_title'=>$cat['title'],
        ));
        
        return $this->view->render();
    }
}