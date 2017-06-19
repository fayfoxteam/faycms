<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use cms\services\user\UserPasswordService;
use cms\services\user\UserService;
use fay\core\Response;

/**
 * 登录
 */
class LoginController extends ApiController{
    /**
     * 登录
     * @parameter string $username 用户名
     * @parameter string $password 密码
     */
    public function index(){
        if($this->input->post()){
            $result = UserPasswordService::service()->checkPassword(
                $this->input->post('username'),
                $this->input->post('password')
            );
            
            if($result['user_id']){
                try{
                    $user = UserService::service()->login($result['user_id']);
                }catch(\Exception $e){
                    Response::notify('error', array(
                        'message'=>$e->getMessage(),
                        'code'=>method_exists($e, 'getDescription') ? $e->getDescription() : '',
                    ));
                }
            }else{
                Response::notify('error', array(
                    'message'=>isset($result['message']) ? $result['message'] : '登录失败',
                    'code'=>isset($result['error_code']) ? $result['error_code'] : '',
                ));
            }
            
            if(!empty($user)){
                Response::notify('success', array(
                    'message'=>'登录成功',
                    'data'=>array(
                        'user'=>array(
                            'id'=>$user['user']['user']['id'],
                            'username'=>$user['user']['user']['username'],
                            'nickname'=>$user['user']['user']['nickname'],
                            'avatar'=>$user['user']['user']['avatar'],
                        ),
                        \F::config()->get('session.ini_set.name')=>session_id(),
                    ),
                ));
            }else{
                Response::notify('error', array(
                    'message'=>'登录失败',
                    'code'=>'',
                ));
            }
        }else{
            Response::notify('error', array(
                'message'=>'登录失败',
                'code'=>'no-post-data',
            ));
        }
    }
    
    /**
     * 登出
     */
    public function logout(){
        UserService::service()->logout();
        
        Response::notify('success', array(
            'message'=>'退出登录',
        ));
    }
}