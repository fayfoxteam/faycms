<?php
namespace cms\modules\tools\controllers;

use cms\library\ToolsController;

class IndexController extends ToolsController{
    
    public function index(){
        $this->layout->subtitle = 'Tools';
        
        $this->view->render();
    }
}