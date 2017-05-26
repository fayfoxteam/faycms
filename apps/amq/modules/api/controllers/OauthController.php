<?php
namespace amq\modules\api\controllers;

use cms\library\ApiController;
use cms\services\user\UserOauthService;
use cms\services\user\UserService;
use fay\core\Response;
use fayoauth\services\OauthAppService;

class OauthController extends ApiController{
    public function am(){
        $oauth = OauthAppService::service()->getOauthService('amq');

        $oauth_user = $oauth->getUser();
        if($user_id = UserOauthService::service()->isLocalUser($oauth_user->getAccessToken()->getAppId(), $oauth_user->getOpenId())){
            //若open id已存在，则不需要重复创建用户
        }else{
            if(!$oauth_user->getNickName()){
                $oauth_user->setNickName('am_' . $oauth_user->getOpenId());
            }
            $user_id = UserOauthService::service()
                ->createUser($oauth_user);
        }

        UserService::service()->login($user_id);

        Response::redirect($this->input->get('redirect', 'trim'));
    }
}