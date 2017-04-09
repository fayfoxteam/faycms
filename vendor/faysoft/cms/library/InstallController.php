<?php
namespace cms\library;

use fay\core\Controller;

class InstallController extends Controller{
    
    public function __construct(){
        parent::__construct();
        
        //重置session.namespace
        $this->config->set('session.namespace', $this->config->get('session.namespace').'_install');
        
        //屏蔽测试堆栈
        $this->config->set('debug', false);
        
        $this->layout_template = 'default';
    }
}