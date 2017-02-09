<?php
namespace fay\services;

use fay\core\Service;
use Gregwar\Captcha\CaptchaBuilder;

class CaptchaService extends Service{
	/**
	 * 生成一张验证码，并输出
	 * @param int $width
	 * @param int $height
	 */
	public static function output($width = 150, $height = 40){
		$builder = new CaptchaBuilder();
		$builder->build(
			$width,
			$height
		);
		\F::session()->set('captcha', strtolower($builder->getPhrase()));
		
		header('Content-type: image/jpeg');
		$builder->output();
		die;
	}
	
	/**
	 * 验证指定验证码是否正确
	 * @param $value
	 * @return bool
	 */
	public static function check($value){
		$builder = new CaptchaBuilder(\F::session()->get('captcha'));
		\F::session()->remove('captcha');
		return $builder->testPhrase($value);
	}
}