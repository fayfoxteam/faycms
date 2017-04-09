<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\services\OptionService;
use fay\core\Response;

class SiteController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'site';
    }
    
    public function setOptions(){
        $this->layout->subtitle = '站点参数';
    
        if($this->input->post()){
            $data = $this->input->post();
            unset($data['_submit']);//提交按钮不用保存
            foreach($data as $key=>$value){
                OptionService::set($key, $value);
            }
            Response::notify('success', '保存成功');
        }
        Response::notify('error', '无数据提交');
    }
    
    /**
     * 系统参数
     */
    public function settings(){
        $this->layout->subtitle = '系统设置';
        
        $this->view->render();
    }
    
    /**
     * 第三方登录参数
     */
    public function oauth(){
        $this->layout->current_directory = 'third-party';
        $this->layout->subtitle = '第三方登录';
        
        $this->view->render();
    }
}