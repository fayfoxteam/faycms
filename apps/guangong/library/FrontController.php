<?php
namespace guangong\library;

use fay\core\Controller;
use fay\core\Http;
use fay\helpers\RequestHelper;
use cms\models\tables\SpiderLogsTable;
use fayoauth\models\tables\OauthUserConnectsTable;
use fayoauth\services\OauthAppService;
use fayoauth\services\OAuthException;
use fayoauth\services\OauthService;
use cms\services\OptionService;
use cms\services\user\UserService;

class FrontController extends Controller{
    public $layout_template = 'frontend';
    
    public function __construct(){
        parent::__construct();
        
        //设置当前用户id
        $this->current_user = \F::session()->get('user.id', 0);
        
        if($spider = RequestHelper::isSpider()){//如果是蜘蛛，记录蜘蛛日志
            SpiderLogsTable::model()->insert(array(
                'spider'=>$spider,
                'url'=>Http::getCurrentUrl(),
                'user_agent'=>isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
                'ip_int'=>RequestHelper::ip2int($this->ip),
                'create_time'=>$this->current_time,
            ));
        }
    }
    
    /**
     * @param \fay\core\Form $form
     */
    public function onFormError($form){
        $errors = $form->getErrors();
        
        if($errors){
            die($errors[0]['message']);
        }
    }
    
    /**
     * 判断是否已登录
     * @return bool
     */
    protected function isLogin(){
        return !!$this->current_user;
    }
    
    /**
     * 检查用户是否登录
     * 若未登录，尝试获取用户openid（用户无感知），若该openid已注册，自动登录
     */
    protected function checkLogin(){
        if($this->isLogin()){
            return true;
        }
        
        $key = 'oauth:weixin';
        $config = OptionService::getGroup($key);
        if(!$config){
            throw new OAuthException("{{$key}} Oauth参数未设置");
        }
        
        if(empty($config['enabled'])){
            throw new OAuthException("{{$key}} Oauth登录已禁用");
        }
        
        $oauth = OauthService::getInstance(
            'weixin',
            $config['app_id'],
            $config['app_secret']
        );
        
        $open_id = $oauth->getOpenId();
        $user_connect = OauthUserConnectsTable::model()->fetchRow(array(
            'oauth_app_id = ?'=>OauthAppService::service()->getIdByAppId($config['app_id']),
            'open_id = ?'=>$open_id,
        ));
        
        if($user_connect){
            UserService::service()->login($user_connect['user_id']);
            return true;
        }else{
            return false;
        }
    }
}