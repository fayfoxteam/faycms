<?php
namespace w\modules\api\controllers;
use fay\core\Controller;
use fay\core\Response;
use fay\helpers\StringHelper;
use fay\models\tables\Tokens;
use fay\models\tables\Users;
use fay\models\User;

class RegController extends Controller {
    public function index(){

    }
    public function getSms(){
        $cellphone = $this->input->post('cellphone');
        if(! $cellphone){
            Response::json('array()','0','手机号码有误','telphone:can-not-be-empty');
        }else{
            $sms = StringHelper::random('numeric','6');
            \F::session()->set('sms',$sms);
            \F::session()->set('cellphone',$cellphone);
            \F::session()->get('sms');
            Response::json(array('sms'=>\F::session()->get('sms')),'1','注册短信已发送成功','sms:send-success');
//                $resp =json_decode(Sms::send($cellphone,$sms.',3','8538'),true);
//               if($resp['resp']['respCode']=='000000'){
//                   Response::json(array('sms'=>\F::session()->get('sms')),'1','注册短信已发送成功','sms:send-success');
//               }else{
//                   Response::json($resp,'0','短信发送失败','sms:send-success');
//               }
        }

    }

    public function reg_do(){
        $cellphone = $this->input->post('cellphone');
        $sms = $this->input->post('sms');
        $conditions = array(
            'cellphone = ?'=>$cellphone,
        );
        $user = Users::model()->fetchRow($conditions);

        //验证码错误
        if(!($cellphone == \F::session()->get('cellphone')) || !($sms ==\F::session()->get('sms'))){
            Response::json(array(),'0','验证码有误','sms:can-is-not-right');
        }
        if($user){
            //如果已存在 登录逻辑
            $conditions = array(
                'token = ?'=>$cellphone,
            );
            // $user_id = Tokens::model()->fetchRow()
            //$token_data['token'] = md5(md5($this->input->post('uuid')).$user['salt'].$user['id']);


            Response::json(array(),'0','手机号码已存在','telphone:can-not-be-exist');
        }else{
            //如果未存在 注册逻辑
            $data['username'] = $cellphone;
            $data['cellphone'] = $cellphone;
            $data['reg_time'] = $this->current_time;
            $data['status'] = Users::STATUS_UNCOMPLETED;
            $data['salt'] = StringHelper::random('alnum', 6);
            $data['password'] = md5($data['salt']);
            $user_id = Users::model()->insert($data);
            //Response::json(array(),'1','注册成功','reg:success');
            if($user_id){
                $user = User::model()->get($user_id);
                $token_data['user_id'] = $user['id'];
                $token_data['token'] = md5(md5($this->input->post('uuid')).$user['salt'].$user['id']);
                $token_data['creat_time'] = $this->current_time;
                $token_data['uuid'] = $this->input->post('uuid');
                Tokens::model()->insert($token_data);
                Response::jsonp(array('token'=>$token_data['token']),'1','注册成功','reg:success');
            }else{
                Response::json(array(),'0','注册失败','reg:fail');
            }
        }

    }
}
