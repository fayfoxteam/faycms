<?php
namespace cms\services\oauth\qq;

use fay\helpers\HttpHelper;
use cms\services\oauth\AccessTokenAbstract;
use cms\services\oauth\OAuthException;
use cms\services\oauth\weixin\QQUser;

class QQAccessToken extends AccessTokenAbstract{
    /**
     * 拉取用户信息
     */
    const USER_INFO_URL = 'https://graph.qq.com/oauth2.0/me';
    
    /**
     * 刷新access_token
     */
    const REFRESH_TOKEN_URL = 'https://graph.qq.com/oauth2.0/token';
    
    /**
     * 应用密钥（QQ在刷新access_token时，需要传app_secret）
     */
    protected $app_secret;
    
    public function __construct($app_id, $params, $app_secret){
        parent::__construct($app_id, $params);
        $this->app_secret = $app_secret;
    }
    
    /**
     * @see \cms\services\oauth\AccessTokenAbstract::getUser()
     * @param string $lang 语言，默认为中文
     * @return QQUser
     * @throws OAuthException
     * @throws \fay\core\ErrorException
     */
    public function getUser($lang = 'zh_CN'){
        if(!$this->check()) {
            $this->refresh();
        }
        
        $response = HttpHelper::getJson(self::USER_INFO_URL, array(
            'access_token'=>$this->params['access_token'],
            'openid'=>$this->params['openid'],
            'lang'=>$lang
        ));
        
        if($response['errcode'] != 0){
            throw new OAuthException($response['errmsg'], $response['errcode']);
        }
        
        return new QQUser($response, $this);
    }
    
    /**
     * @see \cms\services\oauth\AccessTokenAbstract::refresh()
     * @return $this
     * @throws OAuthException
     */
    public function refresh(){
        $response = HttpHelper::getJson(self::REFRESH_TOKEN_URL, array(
            'client_id'=>$this->app_id,
            'client_secret'=>$this->app_secret,
            'grant_type'=>'refresh_token',
            'refresh_token'=>$this->params['refresh_token'],
        ));
        
        if($response['errcode'] != 0){
            throw new OAuthException($response['errmsg'], $response['errcode']);
        }
        
        //更新access_token信息
        $this->params = $response;
        
        return $this;
    }
    
    /**
     * QQ登录没有这样的接口，返回null
     */
    public function check(){
        return null;
    }
}