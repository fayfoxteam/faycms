<?php
namespace fay\models;

use fay\core\Model;
use fay\core\Loader;
use fay\core\ErrorException;

class Sms extends Model{
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
		$config = Option::getGroup('ucpaas');
		if($config['enabled'] == null || empty($config['accountsid']) || empty($config['token']) || empty($config['appid'])){
			throw new ErrorException('云之讯参数未配置');
		}else if(!$config['enabled']){
			return true;
		}
		
		Loader::vendor('ucpaas/Ucpaas.class');
		
		$ucpass = new \Ucpaas($config);
		
		return $ucpass->templateSMS($config['appid'], $to, $template_id, $content);
	}
}