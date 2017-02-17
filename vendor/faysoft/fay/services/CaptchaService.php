<?php
namespace fay\services;

use fay\core\Service;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;

class CaptchaService extends Service{
	/**
	 * 生成一张验证码，并输出
	 * @param int $width
	 * @param int $height
	 * @param int $length
	 */
	public static function output($width = 150, $height = 40, $length = 4){
		$parase_builder = new PhraseBuilder();
		$parase = $parase_builder->build($length);//构造一个指定位数的验证码
		$builder = new CaptchaBuilder($parase);
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