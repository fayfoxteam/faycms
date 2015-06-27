<?php
namespace fay\models;

use fay\core\Model;
use fay\core\Loader;

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
	public function send($address, $subject, $body){
		if(!\F::config()->get('send_email')){
			return;
		}
		
		Loader::vendor('PHPMailer/class.phpmailer');
		$config_email = \F::config()->get('email');
	
		$mail = new \PHPMailer ();
		$mail->IsSMTP ();
		$mail->SMTPDebug = false;
		$mail->Host = $config_email['Host'];
		$mail->SMTPAuth = true;
		$mail->Username = $config_email['Username'];
		$mail->Password = $config_email['Password']; // SMTP password
		$mail->SMTPSecure = $config_email['SMTPSecure']; // Enable encryption, 'ssl' also accepted
		$mail->Port = $config_email['Port'];
		$mail->CharSet = $config_email['CharSet'];
		$mail->From = $config_email['From'];
		$mail->FromName = $config_email['FromName'];
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