<?php
namespace w\modules\api\controllers;


use fay\core\Controller;
use fay\core\Response;
use fay\core\Session;
use fay\helpers\String;
use fay\models\tables\Users;

class RegController extends Controller {
    public function index(){
        echo '111';
    }
    public function getSms(){
        $cellphone = $this->input->post('cellphone');
        if(! $cellphone){
            Response::json('array()','0','手机号码有误','telphone:can-not-be-empty');
        }else{
            $conditions = array(
                'cellphone = ?'=>$cellphone,
            );
            $user = Users::model()->fetchRow($conditions);
            if($user){
                Response::json('array()','0','手机号码已存在','telphone:can-not-be-exist');
            }else{
                $sms = String::random('numeric','6');
                \F::session()->set('sms',$sms);
                \F::session()->set('cellphone',$cellphone);
                \F::session()->get('sms');
                Response::json(array('sms'=> \F::session()->get('sms')),'1','注册短信已发送成功','');
            }
        }
    }

    public function reg_do(){
        $cellphone = $this->input->post('cellphone');
        $sms = $this->input->post('sms');
        if(!($cellphone == \F::session()->get('cellphone')) || !($sms ==\F::session()->get('sms'))){
            Response::json('array()','0','验证码有误','sms:can-is-not-right');
        }else{
            Response::json('array()','1','注册成功','reg:success');
        }
    }
}

