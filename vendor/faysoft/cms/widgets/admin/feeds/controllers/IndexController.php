<?php
namespace cms\widgets\admin\feeds\controllers;

use cms\models\tables\LogsTable;
use fay\widget\Widget;

class IndexController extends Widget{
    public function index(){
        $this->view->logs = LogsTable::model()->fetchAll(array(
            'or'=>array(
                'type = '.LogsTable::TYPE_ERROR,
                'type = '.LogsTable::TYPE_WARMING,
            )
        ), 'id,code,create_time,type', 'id DESC', 20);
        
        return $this->view->render();
    }
    
    public function placeholder(){
        
        return $this->view->render('placeholder');
    }
}