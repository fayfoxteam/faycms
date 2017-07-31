<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;

class SiteController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'site';
    }
    
    /**
     * 系统参数
     */
    public function settings(){
        $this->layout->subtitle = '系统设置';
        
        $this->view->render();
    }

    /**
     * 水印图设置
     */
    public function watermark(){
        $this->layout->subtitle = '图片水印';
        
        $this->view->render();
    }

    /**
     * 云存储
     */
    public function storage(){
        $this->layout->current_directory = 'third-party';
        $this->layout->subtitle = '云存储';

        $this->view->render();
    }

    /**
     * 短信运营商
     */
    public function sms(){
        $this->layout->current_directory = 'third-party';
        $this->layout->subtitle = '短信运营商';

        $this->view->render();
    }
}