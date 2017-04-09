<?php
namespace cms\modules\tools\controllers;

use cms\library\ToolsController;

class QrcodeController extends ToolsController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'qrcode';
    }
    
    public function index(){
        $this->layout->subtitle = 'Qrcode';
        
        $this->view->render();
    }
}