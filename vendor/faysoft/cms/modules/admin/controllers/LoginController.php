<?php
namespace cms\modules\admin\controllers;

use fay\core\Controller;
use cms\services\LogService;
use fay\core\Response;
use cms\models\tables\LogsTable;
use fay\core\Loader;
use cms\services\user\UserService;
use cms\services\user\UserPasswordService;

class LoginController extends Controller{
    public function __construct(){
        parent::__construct();
        $this->config->set('session.namespace', $this->config->get('session.namespace').'_admin');
        
        $this->current_user = \F::session()->get('user.id', 0);
    }
    
    public function index(){
        //不显示debug信息，显示debug信息的话视觉效果上不好看
        $this->config->set('debug', false);
        
        if($this->input->post()){
            $result = UserPasswordService::service()->checkPassword(
                $this->input->post('username'),
                $this->input->post('password'),
                true
            );
            
            if($result['user_id']){
                $user = UserService::service()->login($result['user_id']);
            }
            
            if(!empty($user)){
                LogService::set('admin:action:login.success', array(
                    'fmac'=>isset($_COOKIE['fmac']) ? $_COOKIE['fmac'] : '',
                    'username'=>$this->input->post('username'),
                ));
                if($this->input->get('redirect')){
                    header('location:'.base64_decode($this->input->get('redirect')));
                    die;
                }else{
                    Response::redirect('cms/admin/index/index');
                }
            }else{
                LogService::set('admin:action:login.fail', array(
                    'error_code'=>$result['error_code'],
                    'username'=>$this->input->post('username'),
                    'password'=>$this->input->post('password'),
                ), LogsTable::TYPE_WARMING);
                $this->view->error = $result['message'];
            }
        }
        
        //引入IP地址库
        Loader::vendor('IpLocation/IpLocation.class');
        $this->view->iplocation = new \IpLocation();
        
        $this->view->render('index');
    }
    
    public function logout(){
        UserService::service()->logout();
        Response::redirect('cms/admin/login/index');
    }
}