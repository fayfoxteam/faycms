<?php
namespace apidoc\modules\frontend\controllers;

use apidoc\library\FrontController;
use apidoc\models\tables\ApidocModelsTable;

class IndexController extends FrontController{
    public function index(){
        //若指定了app_id，则设置Cookie
        if($this->input->get('app_id')){
            \F::cookie()->set('apidoc_current_app', $this->app_id);
        }
        
        $this->layout->assign(array(
            'title'=>'概述',
            'canonical'=>$this->view->url(),
        ));
        
        return $this->view->assign(array(
            'models'=>ApidocModelsTable::model()->fetchAll('id < 1000', 'name,description,sample', 'id')
        ))->render('apidoc/frontend/index/index');
    }
}