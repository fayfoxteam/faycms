<?php
namespace fayoauth\services\am;

use fay\core\Response;
use fayoauth\services\OAuthException;
use fayoauth\services\OauthService;

class AmOauthService extends OauthService{
    /**
     * 获取Access Token
     * @return AmAccessToken
     * @throws OAuthException
     */
    public function getAccessToken(){
        $client = new AmClient($this->getAppId(), $this->getAppSecret());
        if(!$code = \F::input()->get('code')){
            //跳转到拉取授权页面
            Response::redirect($client->getAuthorizeUrl());
        }
    
        return $client->getAccessToken($code);
    }
    
    /**
     * 获取用户信息
     * @return AmUser
     * @throws OAuthException
     */
    public function getUser(){
        return $this->getAccessToken()->getUser();
    }
    
    /**
     * 获取openId
     * @return string
     */
    public function getOpenId(){
        return $this->getAccessToken()->getOpenId();
    }
}