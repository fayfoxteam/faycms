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
	 */
	public static function send($to, $content){
		if(!\F::config()->get('send_sms')){
			return;
		}
		
		$config = Option::getTeam('ucpaas');
		
		if(empty($config['accountsid']) || empty($config['token'])){
			throw new ErrorException('云之讯参数未配置');
		}
		
		Loader::vendor('ucpaas/Ucpaas.class');
		$config = Option::getTeam('ucpaas');
		
		$ucpass = new \Ucpaas($config);
		
		if (!$mail->Send ()){
			return $mail->ErrorInfo;
		}
		return true;
	}
}