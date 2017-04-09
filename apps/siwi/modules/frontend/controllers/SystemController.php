<?php
namespace siwi\modules\frontend\controllers;

use siwi\library\FrontController;
use cms\models\tables\UsersTable;

class SystemController extends FrontController{
    public function isUsernameNotExist(){
        $param = $this->input->post('param');
        $conditions = array(
            'username = ?'=>$param,
            'delete_time = 0',
        );
        if($this->input->request('id')){
            $conditions['id != ?'] = $this->input->request('id', 'intval');
        }
        $user = UsersTable::model()->fetchRow($conditions);
        if($user){
            echo '该用户名已被注册';
        }else{
            echo 'y';
        }
    }
    
    public function isUsernameExist(){
        $param = $this->input->post('param');
        $conditions = array(
            'username = ?'=>$param,
            'delete_time = 0',
        );
        if($this->input->request('id')){
            $conditions['id != ?'] = $this->input->request('id', 'intval');
        }
        $user = UsersTable::model()->fetchRow($conditions);
        if($user){
            echo 'y';
        }else{
            echo '该用户名不存在';
        }
    }
    
    public function getHeader(){
        $this->layout_template = '_header';
        $this->view->render();
    }
}