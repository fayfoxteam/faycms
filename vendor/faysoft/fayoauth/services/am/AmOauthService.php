<?php
namespace fayoauth\services\am;

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
        
        if($this->redirect_uri){
            //若指定回调地址，则设置回调地址，默认跳转到当前页
            $client->setRedirectUri($this->redirect_uri);
        }
        
        if(!$code = \F::input()->get('code')){
            //跳转到拉取授权页面
            $this->response->redirect($client->getAuthorizeUrl());
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
        //爱名在获取AccessToken的时候没有返回Open Id，需要获取用户详情的时候才会返回UserId作为Open Id
        return $this->getAccessToken()->getUser()->getOpenId();
    }
}