<?php
namespace fayoauth\modules\admin\controllers;

use cms\library\AdminController;
use fay\common\ListView;
use fay\core\Sql;
use fayoauth\models\tables\OauthAppsTable;

/**
 * 第三方信息管理
 */
class AppController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'oauth';
    }
    
    public function index(){
        $this->layout->subtitle = '添加APP';
    
        $this->_setListview();
    
        $this->form()->setModel(OauthAppsTable::model());
        $this->view->render();
    }
    
    public function create(){
        
    }
    
    public function edit(){
        
    }
    
    public function delete(){
        
    }
    
    public function remove(){
        
    }
    
    /**
     * 设置右侧列表
     */
    private function _setListview(){
        $sql = new Sql();
        $sql->from(array('a'=>'oauth_apps'))
            ->order('a.id DESC');
        
        $this->view->listview = new ListView($sql);
    }
}