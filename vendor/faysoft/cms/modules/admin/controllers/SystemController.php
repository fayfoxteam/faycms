<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\forms\SettingForm;
use cms\models\tables\UsersTable;
use cms\services\SettingService;
use fay\core\Response;

class SystemController extends AdminController{
    public function isMoboleExist(){
        if(UsersTable::model()->has(array(
            'mobile = ?'=>$this->input->post('value', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval')
        ))){
            return Response::json('', 0, '该手机号码已被注册');
        }else{
            return Response::json();
        }
    }
    
    public function isEmailExist(){
        if(UsersTable::model()->has(array(
            'email = ?'=>$this->input->post('value', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval')
        ))){
            return Response::json('', 0, '该邮箱已被注册');
        }else{
            return Response::json();
        }
    }
    
    public function isUsernameExist(){
        if(UsersTable::model()->has(array(
            'username = ?'=>$this->input->post('value', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval')
        ))){
            return Response::json('', 0, '该用户名已被注册');
        }else{
            return Response::json();
        }
    }
    
    public function setting(){
        if($this->input->post()){
            if($this->form('setting')
                ->setModel(SettingForm::model())
                ->check()){
                $data = $this->form('setting')->getAllData();
                $key = $data['_key'];
                unset($data['_key'], $data['_submit']);
                SettingService::service()->set($key, $data);
                return Response::notify(Response::NOTIFY_SUCCESS, '设置保存成功');
            }else{
                return Response::notify(Response::NOTIFY_FAIL, '异常的数据格式');
            }
        }else{
            return Response::notify(Response::NOTIFY_FAIL, '无数据被提交');
        }
    }
}