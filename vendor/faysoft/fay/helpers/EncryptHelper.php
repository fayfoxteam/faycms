<?php
namespace fay\helpers;

class EncryptHelper{
	public static function encode($data){
		return base64_encode(self::mcrypt_encode($data, \F::config()->get('encryption_key')));
	}
	
	public static function decode($data){
		return self::mcrypt_decode(base64_decode($data), \F::config()->get('encryption_key'));
	}
	
	public static function mcrypt_encode($data, $key){
		$init_size = mcrypt_get_iv_size(self::_get_cipher(), self::_get_mode());
		$init_vect = mcrypt_create_iv($init_size, MCRYPT_RAND);
		return self::_add_cipher_noise($init_vect.mcrypt_encrypt(self::_get_cipher(), $key, $data, self::_get_mode(), $init_vect), $key);
	}
	
	public static function mcrypt_decode($data, $key){
		$data = self::_remove_cipher_noise($data, $key);
		$init_size = mcrypt_get_iv_size(self::_get_cipher(), self::_get_mode());
	
		if ($init_size > strlen($data)){
			return false;
		}
	
		$init_vect = substr($data, 0, $init_size);
		$data = substr($data, $init_size);
		return rtrim(mcrypt_decrypt(self::_get_cipher(), $key, $data, self::_get_mode(), $init_vect), "\0");
	}
	
	private static function _get_cipher(){
		return MCRYPT_RIJNDAEL_256;
	}
	
	private static function _get_mode(){
		return MCRYPT_MODE_CBC;
	}
	
	private static function _add_cipher_noise($data, $key){
		$keyhash = md5($key);
		$keylen = strlen($keyhash);
		$str = '';
	
		for($i = 0, $j = 0, $len = strlen($data); $i < $len; ++$i, ++$j){
			if($j >= $keylen){
				$j = 0;
			}
	
			$str .= chr((ord($data[$i]) + ord($keyhash[$j])) % 256);
		}
	
		return $str;
	}
	
	private static function _remove_cipher_noise($data, $key){
		$keyhash = md5($key);
		$keylen = strlen($keyhash);
		$str = '';
	
		for($i = 0, $j = 0, $len = strlen($data); $i < $len; ++$i, ++$j){
			if($j >= $keylen){
				$j = 0;
			}
	
			$temp = ord($data[$i]) - ord($keyhash[$j]);
	
			if($temp < 0){
				$temp = $temp + 256;
			}
	
			$str .= chr($temp);
		}
	
		return $str;
	}
}