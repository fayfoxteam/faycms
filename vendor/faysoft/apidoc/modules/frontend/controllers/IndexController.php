<?php
namespace apidoc\modules\frontend\controllers;

use apidoc\library\FrontController;
use apidoc\models\tables\ApidocModelsTable;
use cms\services\OptionService;

class IndexController extends FrontController{
    public function index(){
        $this->layout->assign(array(
            'title'=>'概述',
            'canonical'=>$this->view->url(),
        ));
        
        $this->view->assign(array(
            'models'=>ApidocModelsTable::model()->fetchAll('id < 1000', 'name,description,sample', 'id')
        ))->render('apidoc/frontend/index/index');
    }
}