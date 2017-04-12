<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fayoauth\services\OAuthException;
use fayoauth\services\OauthService;
use cms\services\OptionService;
use cms\services\user\UserOauthService;
use cms\services\user\UserService;

class OauthController extends ApiController{
    public function weixin(){
        $key = 'oauth:weixin';
        $config = OptionService::getGroup($key);
        if(!$config){
            throw new OAuthException("{{$key}} Oauth参数未设置");
        }
        
        if(empty($config['enabled'])){
            throw new OAuthException("{{$key}} Oauth登录已禁用");
        }
        
        $oauth_user = OauthService::getInstance(
            'weixin',
            $config['app_id'],
            $config['app_secret']
        )
            ->getAccessToken()//获取Access Token
            ->getUser();
        if($user_id = UserOauthService::service()->isLocalUser($oauth_user->getAccessToken()->getAppId(), $oauth_user->getOpenId())){
            //若open id已存在，则不需要重复创建用户
        }else{
            $user_id = UserOauthService::service()
                ->createUser($oauth_user);
        }
        
        UserService::service()->login($user_id);
        
        Response::redirect('recruit/#9');
    }
}