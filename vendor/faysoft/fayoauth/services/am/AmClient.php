<?php
namespace fayoauth\services\am;

use fay\core\Request;
use fay\helpers\HttpHelper;
use fay\helpers\StringHelper;
use fayoauth\services\ClientAbstract;
use fayoauth\services\OAuthException;

class AmClient extends ClientAbstract{
    /**
     * 通过code换取网页授权access_token
     */
    const ACCESS_TOKEN_URL = 'https://oauth.22.cn/Oauth2/AccessToken';
    
    /**
     * 用户授权URL
     */
    const AUTHORIZE_URL = 'https://oauth.22.cn/Oauth2/Authorize';
    
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
            'redirect_uri'=>$this->redirect_uri ?: Request::getCurrentUrl(),
            'response_type'=>'code',
            'state'=>$this->state
        ));
    }
    
    /**
     * @see \fayoauth\services\ClientAbstract
     * @param string $code
     * @param null $state
     * @return AmAccessToken
     * @throws OAuthException
     * @throws \fay\core\ErrorException
     */
    public function getAccessToken($code, $state = null){
        $state || $state = \F::input()->get('state');
        
        if(!$this->state_manager->check($state)){
            throw new OAuthException('爱名OAuthState参数异常');
        }
        
        $response = HttpHelper::postJson(self::ACCESS_TOKEN_URL, array(
            'client_id'=>$this->app_id,
            'client_secret'=>$this->app_secret,
            'code'=>$code,
            'grant_type'=>'authorization_code',
            'redirect_uri'=>$this->redirect_uri ?: HTTP_USER_AGENT::getCurrentUrl(),
        ));
        
        if(!isset($response['access_token'])){
            throw new OAuthException(
                isset($response['error']) ? $response['error'] : '爱名OAuth授权失败',
                isset($response['errorcode']) ? $response['errorcode'] : json_encode($response)
            );
        }
        return new AmAccessToken($this->app_id, $response);
    }
}