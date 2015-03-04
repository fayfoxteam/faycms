<?php
namespace tzcs\modules\frontend\controllers;

use tzcs\library\FrontController;
use fay\models\Page;
class PageController extends FrontController{
    public function item(){
        $id = $this->input->get('id');
        if (is_numeric($id)){
            $id = intval($id);
            $this->view->content = Page::model()->get($id);
            
        }else{
            $this->view->content = Page::model()->getByAlias($id);
        }
        
        $this->view->render();
    }
}