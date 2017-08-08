<?php
namespace cms\widgets\admin\change_app\controllers;

use fay\core\Response;
use fay\helpers\LocalFileHelper;
use fay\widget\Widget;

class IndexController extends Widget{
    public function index(){
        $apps = LocalFileHelper::getFileList(APPLICATION_PATH.'..');
        $options = array();
        foreach($apps as $app){
            if($app['is_dir']){
                $options[$app['name']] = $app['name'];
            }
        }
        $this->view->options = $options;
        return $this->view->render();
    }
    
    public function change(){
        if($this->input->post('app')){
            setcookie('__app', $this->input->post('app'), null, '/');
            $this->response->redirect('cms/admin/index/index');
        }
    }
}