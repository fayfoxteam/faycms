<?php
namespace fayoauth\services\am;

use fay\helpers\HttpHelper;
use fayoauth\services\AccessTokenAbstract;
use fayoauth\services\OAuthException;

class AmAccessToken extends AccessTokenAbstract{
    /**
     * 拉取用户信息
     */
    const USER_INFO_URL = 'https://open2.22.cn/user/info';
    
    /**
     * 刷新access_token
     */
    const REFRESH_TOKEN_URL = 'https://oauth.22.cn/Oauth2/AccessToken';
    
    /**
     * @see \fayoauth\services\AccessTokenAbstract::getUser()
     * @param string $lang 语言，默认为中文
     * @return AmUser
     * @throws OAuthException
     */
    public function getUser(){
        if(!$this->check()) {
            $this->refresh();
        }
        
        $response = HttpHelper::getJson(self::USER_INFO_URL, array(
            'access_token'=>$this->params['access_token'],
        ));
        
        if(!isset($response['UserId'])){
            throw new OAuthException(
                isset($response['error']) ? $response['error'] : '爱名OAuth拉去用户信息失败',
                isset($response['errorcode']) ? $response['errorcode'] : json_encode($response)
            );
        }
        
        return new AmUser($response, $this);
    }
    
    /**
     * @see \fayoauth\services\AccessTokenAbstract::refresh()
     * @return $this
     * @throws OAuthException
     */
    public function refresh(){
        $response = HttpHelper::getJson(self::REFRESH_TOKEN_URL, array(
            'appid'=>$this->app_id,
            'grant_type'=>'refresh_token',
            'refresh_token'=>$this->params['refresh_token'],
        ));
        
        if(isset($response['errcode'])){
            throw new OAuthException($response['errmsg'], $response['errcode']);
        }
        
        //更新access_token信息
        $this->params = $response;
        
        return $this;
    }
    
    /**
     * @see \fayoauth\services\AccessTokenAbstract::check()
     * @return bool
     * @throws OAuthException
     */
    public function check(){
        //不支持检查
        throw new OAuthException('爱名OAuth不支持检查token是否有效');
    }

    /**
     * 爱名比较奇葩，直接用UserId当OpenId用
     * @return string
     */
    public function getOpenId(){
        return $this->getParam('UserId');
    }
}