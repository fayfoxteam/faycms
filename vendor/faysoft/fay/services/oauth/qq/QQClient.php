<?php
namespace fay\services\oauth\qq;

use fay\core\Http;
use fay\helpers\HttpHelper;
use fay\helpers\StringHelper;
use fay\services\oauth\ClientAbstract;
use fay\services\oauth\OAuthException;

class QQClient extends ClientAbstract{
    /**
     * 通过code换取网页授权access_token
     */
    const ACCESS_TOKEN_URL = 'https://graph.qq.com/oauth2.0/token';
    
    /**
     * 用户授权URL
     */
    const AUTHORIZE_URL = 'https://graph.qq.com/oauth2.0/authorize';
    
    /**
     * 获取授权URL
     */
    public function getAuthorizeUrl(){
        if($this->state === null){
            $this->state = StringHelper::random();
        }
        
        $this->state_manager->setState($this->state);
        
        return HttpHelper::combineURL(self::AUTHORIZE_URL, array(
            'client_id'=>$this->app_id,
            'redirect_uri'=>$this->redirect_uri ?: Http::getCurrentUrl(),
            'response_type'=>'code',
            'scope'=>$this->scope ?: 'get_user_info',
            'state'=>$this->state
        ));
    }
    
    /**
     * @see \fay\services\oauth\ClientAbstract
     * @param string $code
     * @param null $state
     * @return QQAccessToken
     * @throws OAuthException
     * @throws \fay\core\ErrorException
     */
    public function getAccessToken($code, $state = null){
        $state || $state = \F::input()->get('state');
        
        if(!$this->state_manager->check($state)){
            throw new OAuthException('微信登录State参数异常');
        }
        
        $response = HttpHelper::getJson(self::ACCESS_TOKEN_URL, array(
            'client_id'=>$this->app_id,
            'client_secret'=>$this->app_secret,
            'code'=>$code,
            'grant_type'=>'authorization_code',
            'redirect_uri'=>$this->redirect_uri ?: Http::getCurrentUrl(),//与获取code时传入的redirect_uri保持一致
        ));
        
        if($response['errcode'] != 0){
            throw new OAuthException($response['errmsg'], $response['errcode']);
        }
        return new QQAccessToken($this->app_id, $response, $this->app_secret);
    }
}