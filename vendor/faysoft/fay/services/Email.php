<?php
namespace fay\services;

use fay\core\Service;
use fay\core\ErrorException;

class Email extends Service{
	/**
	 * @param string $class_name
	 * @return Email
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 发送一封邮件
	 * @param string|array $address 邮箱地址
	 * @param string $subject 邮件标题
	 * @param string $body 邮件内容
	 * @return bool|string
	 * @throws ErrorException
	 */
	public static function send($address, $subject, $body){
		$config = Option::getGroup('email');
		if($config['enabled'] === null || empty($config['Host']) ||
			empty($config['Username']) || empty($config['Password']) ||
			empty($config['Port'])){
			throw new ErrorException('Email参数未配置');
		}else if(!$config['enabled']){
			return true;
		}
		
		$mail = new \PHPMailer ();
		$mail->isSMTP ();
		$mail->SMTPDebug = false;
		$mail->Host = $config['Host'];
		$mail->SMTPAuth = true;
		$mail->Username = $config['Username'];
		$mail->Password = $config['Password']; // SMTP password
		$mail->SMTPSecure = empty($config['SMTPSecure']) ? '' : $config['SMTPSecure']; // Enable encryption, 'ssl' also accepted
		$mail->Port = $config['Port'];
		$mail->CharSet = 'utf8';
		$mail->From = $config['Username'];
		$mail->FromName = empty($config['FromName']) ? '' : $config['FromName'];
		if(is_array($address)){
			foreach($address as $a){
				$mail->addAddress($a);
			}
		}else{
			$mail->addAddress($address); // Add a recipient
		}
	
		$mail->isHTML(true); // Set email format to HTML
	
		$mail->Subject = $subject;
		$mail->Body = $body;
	
		if (!$mail->send ()){
			return $mail->ErrorInfo;
		}
		return true;
	}
}