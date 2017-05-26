<?php
namespace amq\modules\api\controllers;

use cms\library\ApiController;
use fayoauth\services\OauthAppService;

class OauthController extends ApiController{
    public function am(){
        $oauth = OauthAppService::service()->getOauthService('amq');

        $open_id = $oauth->getOpenId();
        dd($open_id);
    }
    
    public function test(){
        $oauth = OauthAppService::service()->getOauthService('amq');

        $open_id = $oauth->getOpenId();
        dd($open_id);
    }
}