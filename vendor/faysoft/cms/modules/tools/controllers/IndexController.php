<?php
namespace cms\modules\tools\controllers;

use cms\library\ToolsController;
use fay\core\Loader;
use fay\helpers\DeviceHelper;

class IndexController extends ToolsController{
    
    public function index(){
        $this->layout->subtitle = 'Tools';
        
        $this->view->render();
    }
}