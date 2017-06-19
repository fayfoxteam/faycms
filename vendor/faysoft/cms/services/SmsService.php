<?php
namespace cms\services;

use fay\core\ErrorException;
use fay\core\Loader;
use fay\core\Service;

class SmsService extends Service{
    /**
     * 发送一个短信
     * @param string $to 收短信手机
     * @param string $content 短信内容
     * @param $template_id
     * @return mixed
     * @throws ErrorException
     * @throws \Exception
     */
    public static function send($to, $content, $template_id){
        $config = OptionService::getGroup('ucpaas');
        if($config['enabled'] == null || empty($config['accountsid']) || empty($config['token']) || empty($config['appid'])){
            throw new ErrorException('云之讯参数未配置');
        }else if(!$config['enabled']){
            return true;
        }
        
        Loader::vendor('Ucpaas/Ucpaas.class');
        
        $ucpass = new \Ucpaas($config);
        
        return $ucpass->templateSMS($config['appid'], $to, $template_id, $content);
    }
}