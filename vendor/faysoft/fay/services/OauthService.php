<?php
namespace fay\services;

use fay\core\Response;
use fay\core\Service;
use fay\services\oauth\weixin\WeixinClient;

class OauthService extends Service{
    /**
     * @param string $class_name
     * @return OauthService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    /**
     * 获取微信Access Token
     * @return oauth\weixin\WeixinAccessToken
     * @throws oauth\OAuthException
     */
    public function getWeixinAccessToken(){
        $app_id = 'wxad76a044d8fad0ed';
        $app_secret = '88efdec5df431446c3c42a8ee4004b9d';
    
        $client = new WeixinClient($app_id, $app_secret);
        if(!$code = \F::input()->get('code')){
            //需要获取用户信息（默认为：snsapi_base）
            $client->setScope('snsapi_userinfo');
        
            //跳转到微信拉取授权
            Response::redirect($client->getAuthorizeUrl());
        }
    
        return $client->getAccessToken($code);
    }
}