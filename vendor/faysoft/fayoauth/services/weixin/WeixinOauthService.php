<?php
namespace fayoauth\services\weixin;

use fay\core\Response;
use fayoauth\services\OAuthException;
use fayoauth\services\OauthService;

class WeixinOauthService extends OauthService{
    /**
     * 获取微信Access Token
     * @param string $scope
     *  - snsapi_base 只能获取openId，不需要用户授权，用户无感知
     *  - snsapi_userinfo 可以获取到用户昵称，头像等信息，需要用户授权
     * @return WeixinAccessToken
     * @throws OAuthException
     */
    public function getAccessToken($scope = 'snsapi_userinfo'){
        $client = new WeixinClient($this->getAppId(), $this->getAppSecret());
        if(!$code = \F::input()->get('code')){
            $client->setScope($scope);
        
            //跳转到微信拉取授权
            Response::redirect($client->getAuthorizeUrl());
        }
    
        return $client->getAccessToken($code);
    }
    
    /**
     * 获取微信用户信息
     * @return WeixinUser
     * @throws OAuthException
     */
    public function getUser(){
        return $this->getAccessToken('snsapi_userinfo')->getUser();
    }
    
    /**
     * 获取openId
     * @return string
     */
    public function getOpenId(){
        return $this->getAccessToken('snsapi_base')->getOpenId();
    }
}