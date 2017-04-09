<?php
namespace cms\widgets\feeds\controllers;

use fay\widget\Widget;
use cms\models\tables\LogsTable;

class IndexController extends Widget{
    public function index(){
        $this->view->logs = LogsTable::model()->fetchAll(array(
            'or'=>array(
                'type = '.LogsTable::TYPE_ERROR,
                'type = '.LogsTable::TYPE_WARMING,
            )
        ), 'id,code,create_time,type', 'id DESC', 20);
        
        $this->view->render();
    }
    
    public function placeholder(){
        
        $this->view->render('placeholder');
    }
}