<?php
namespace cms\widgets\admin\user_info\controllers;

use fay\core\Loader;
use fay\widget\Widget;

class IndexController extends Widget{
    public function index(){
        //引入IP地址库
        Loader::vendor('IpLocation/IpLocation.class');
        $this->view->iplocation = new \IpLocation();
        
        return $this->view->render();
    }
    
    public function placeholder(){
        
        return $this->view->render('placeholder');
    }
}