<?php
namespace amq\modules\api\controllers;

use cms\library\ApiController;
use fay\helpers\UrlHelper;
use fayoauth\services\OauthAppService;

class OauthController extends ApiController{
    public function am(){
        $oauth = OauthAppService::service()->getOauthService('amq');
        $oauth->setRedirectUri(UrlHelper::createUrl('api/oauth/test'));

        dd($oauth->getOpenId());
    }
    
    public function test(){
        $oauth = OauthAppService::service()->getOauthService('amq');

        dd($oauth->getUser()->getParams());
    }
}