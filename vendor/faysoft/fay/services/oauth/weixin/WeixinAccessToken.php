<?php
namespace fay\services\oauth\weixin;

use fay\helpers\HttpHelper;
use fay\services\oauth\AccessTokenAbstract;
use fay\services\oauth\OAuthException;

class WeixinAccessToken extends AccessTokenAbstract{
    /**
     * 拉取用户信息
     */
    const USER_INFO_URL = 'https://api.weixin.qq.com/sns/userinfo';
    
    /**
     * 刷新access_token
     */
    const REFRESH_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';
    
    /**
     * 检验授权凭证（access_token）是否有效
     */
    const IS_VALID_URL = 'https://api.weixin.qq.com/sns/auth';
    
    /**
     * @see \fay\services\oauth\AccessTokenAbstract::getUser()
     * @param string $lang 语言，默认为中文
     * @return WeixinUser
     * @throws OAuthException
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
        
        if(isset($response['errcode'])){
            throw new OAuthException($response['errmsg'], $response['errcode']);
        }
        
        return new WeixinUser($response, $this);
    }
    
    /**
     * @see \fay\services\oauth\AccessTokenAbstract::refresh()
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
     * @see \fay\services\oauth\AccessTokenAbstract::check()
     * @return bool
     * @throws OAuthException
     */
    public function check(){
        $response = HttpHelper::getJson(self::IS_VALID_URL, array(
            'access_token' => $this->params['access_token'],
            'openid' => $this->params['openid'],
        ));
        
        return $response['errmsg'] === 'ok';
    }
}