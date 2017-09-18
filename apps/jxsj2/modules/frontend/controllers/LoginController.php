<?php
namespace jxsj2\modules\frontend\controllers;

use jxsj2\library\FrontController;
use fay\core\Response;
use cms\services\user\UserService;

class LoginController extends FrontController{
    public function index(){
        if($this->input->post()){
            if($this->input->post('vcode') && ($this->input->post('vcode', 'strtolower') != \F::session()->get('vcode'))){
                Response::notify(Response::NOTIFY_FAIL, '验证码不正确', array('login'));
            }
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $result = UserService::service()->login($username, $password);
            if($result['status']){
                if($this->input->get('redirect')){
                    header('location:'.base64_decode($this->input->get('redirect')));
                    die;
                }else{
                    $this->response->redirect('user/exam');
                }
            }else{
                if($this->input->get('redirect')){
                    Response::notify(Response::NOTIFY_FAIL, $result['message'], array('login', array(
                        'redirect'=>$this->input->get('redirect', null, false),
                    )));
                }else{
                    Response::notify(Response::NOTIFY_FAIL, $result['message'], array('login'));
                }
            }
        }
        
        $this->layout_template = null;
        return $this->view->render();
    }
}