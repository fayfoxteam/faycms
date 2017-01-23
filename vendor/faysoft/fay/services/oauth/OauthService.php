<?php
namespace fay\services\oauth;

use fay\core\Response;
use fay\core\Service;
use fay\services\oauth\weixin\WeixinClient;
use fay\services\OptionService;

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
     * @return \fay\services\oauth\weixin\WeixinAccessToken
     * @throws OAuthException
     */
    public function getWeixinAccessToken(){
        $config = OptionService::getGroup('oauth:weixin');
        if(!$config){
            throw new OAuthException('微信登录参数未设置');
        }
        
        if(empty($config['enabled'])){
            throw new OAuthException('微信登录已禁用');
        }
    
        $client = new WeixinClient($config['app_id'], $config['app_secret']);
        if(!$code = \F::input()->get('code')){
            //需要获取用户信息（默认为：snsapi_base）
            $client->setScope('snsapi_userinfo');
        
            //跳转到微信拉取授权
            Response::redirect($client->getAuthorizeUrl());
        }
    
        return $client->getAccessToken($code);
    }
}