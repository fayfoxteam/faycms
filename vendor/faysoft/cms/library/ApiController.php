<?php
namespace cms\library;

use fay\core\Controller;
use fay\core\Http;
use fay\core\Response;

/**
 * API积累
 */
class ApiController extends Controller{
    public function __construct(){
        parent::__construct();
        
        $this->current_user = \F::session()->get('user.id', 0);
    }
    
    /**
     * 判断是否已登录
     * @return bool
     */
    protected function isLogin(){
        return !!$this->current_user;
    }
    
    /**
     * 判断是否已登录，若未登录，直接返回需要登录的json
     */
    protected function checkLogin(){
        if(!$this->isLogin()){
            Response::json('', 0, '请先登录', 'login-request');
        }
    }
    
    /**
     * 表单验证，若发生错误，返回第一个报错信息
     * 调用该函数前需先设置表单验证规则
     * @param \fay\core\Form $form
     */
    public function onFormError($form){
        $error = $form->getFirstError();
        Response::notify('error', array(
            'message'=>$error['message'],
            'code'=>$error['code'],
        ));
    }
    
    /**
     * 检查http method，若不符合，直接返回错误提示
     * @param string $method
     */
    public function checkMethod($method){
        $method = strtoupper($method);
        if(Http::getMethod() != $method){
            Response::notify('error', array(
                'message'=>"请以{$method}方式发起请求",
                'code'=>'http-method-error',
            ));
        }
    }
}