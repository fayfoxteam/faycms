<?php
namespace fay\models;

use fay\core\Model;
use fay\core\Loader;
use fay\core\ErrorException;

class Email extends Model{
	/**
	 * @return Email
	 */
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 发送一封邮件
	 * @param string|array $address 邮箱地址
	 * @param string $subject 邮件标题
	 * @param string $body 邮件内容
	 */
	public static function send($address, $subject, $body){
		if(!\F::config()->get('send_email')){
			return;
		}
		
		Loader::vendor('PHPMailer/class.phpmailer');
		$email_config = Option::getTeam('email');
		
		if(empty($email_config['Host']) || empty($email_config['Username']) ||
			empty($email_config['Password']) || empty($email_config['Port'])){
			throw new ErrorException('Email信息未配置');
		}
		
		$mail = new \PHPMailer ();
		$mail->IsSMTP ();
		$mail->SMTPDebug = false;
		$mail->Host = $email_config['Host'];
		$mail->SMTPAuth = true;
		$mail->Username = $email_config['Username'];
		$mail->Password = $email_config['Password']; // SMTP password
		$mail->SMTPSecure = empty($email_config['SMTPSecure']) ? '' : $email_config['SMTPSecure']; // Enable encryption, 'ssl' also accepted
		$mail->Port = $email_config['Port'];
		$mail->CharSet = 'utf8';
		$mail->From = $email_config['Username'];
		$mail->FromName = empty($email_config['FromName']) ? '' : $email_config['FromName'];
		if(is_array($address)){
			foreach($address as $a){
				$mail->AddAddress($a);
			}
		}else{
			$mail->AddAddress($address); // Add a recipient
		}
	
		$mail->IsHTML(true); // Set email format to HTML
	
		$mail->Subject = $subject;
		$mail->Body = $body;
	
		if (!$mail->Send ()){
			return $mail->ErrorInfo;
		}
		return true;
	}
}