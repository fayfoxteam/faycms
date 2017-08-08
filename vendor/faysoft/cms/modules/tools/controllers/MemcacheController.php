<?php
namespace cms\modules\tools\controllers;

use cms\library\ToolsController;
use fay\core\Response;

class MemcacheController extends ToolsController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'memcache';
    
        //登陆检查，仅超级管理员可访问本模块
        $this->isLogin();
    }
    
    public function index(){
        $this->layout->subtitle = 'Memcache';
        
        $this->layout->sublink = array(
            'uri'=>array('cms/tools/memcache/flush'),
            'text'=>'清空缓存',
        );
        
        //单服务器模式
        $slabs = @\F::cache()->getDriver('memcache')->_cache->getExtendedStats('slabs');
        $first_slab = current($slabs);
        $this->view->slabs = $first_slab ? $first_slab : array();
    
        return $this->view->render();
    }
    
    public function delete(){
        \F::cache()->delete($this->input->get('key'), 'memcache');
        Response::goback();
    }
    
    public function flush(){
        \F::cache()->flush(null, 'memcache');
        Response::goback();
    }
}