<?php
namespace cms\widgets\admin\check_system\controllers;

use fay\widget\Widget;

class IndexController extends Widget{
    
    public function index(){
        $this->view->writable = array(
            'uploads'=>is_writable('./../uploads'),
            'public/uploads'=>is_writable('./uploads'),
            'runtimes'=>is_writable(APPLICATION_PATH . 'runtimes'),
        );
        
        $this->view->extensions = get_loaded_extensions();
        
        $mysql_version = $this->db->fetchRow('SELECT VERSION() AS version');
        $this->view->mysql_version = $mysql_version['version'];
        
        $this->view->render();
    }
}