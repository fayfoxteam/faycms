<?php
namespace tzcs\modules\frontend\controllers;

use tzcs\library\FrontController;
use fay\models\Page;
use fay\core\Response;
class PageController extends FrontController{
    public function item(){
        $id = $this->input->get('id');
        if (is_numeric($id)){
            $id = intval($id);
            $content = Page::model()->get($id);
        }else{
            $content = Page::model()->getByAlias($id);
        }
        if (empty($content)){
            Response::showError('页面不存在！');
        }
        
        $this->view->content = $content;
        
        $this->view->render();
    }
}