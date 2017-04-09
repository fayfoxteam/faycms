<?php
namespace shinecolor\modules\admin\controllers;

use cms\library\AdminController;
use cms\services\OptionService;
use cms\services\FlashService;

class YsiteController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'site';
    }
    
    public function options(){
        $this->layout->subtitle = '站点参数';
        
        if($this->input->post()){
            foreach($this->input->post() as $key=>$value){
                OptionService::set($key, $value);
            }
            FlashService::set('更新成功', 'success');
        }
        
        $this->view->render();
    }
}