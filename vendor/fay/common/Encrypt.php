<?php
namespace fay\common;

use fay\core\FBase;

/**
 * 一个加密类，需要开启php_encrypt扩展才能用
 */
class Encrypt extends FBase{
	public $key = '';
	
	public function __construct(){
		$this->key = \F::app()->config('encryption_key');
	}

	public function encode($data){
		return base64_encode($this->mcrypt_encode($data, $this->key));
	}

	public function decode($data){
		return $this->mcrypt_decode(base64_decode($data), $this->key);
	}

	public function mcrypt_encode($data, $key){
		$init_size = mcrypt_get_iv_size($this->_get_cipher(), $this->_get_mode());
		$init_vect = mcrypt_create_iv($init_size, MCRYPT_RAND);
		return $this->_add_cipher_noise($init_vect.mcrypt_encrypt($this->_get_cipher(), $key, $data, $this->_get_mode(), $init_vect), $key);
	}

	public function mcrypt_decode($data, $key){
		$data = $this->_remove_cipher_noise($data, $key);
		$init_size = mcrypt_get_iv_size($this->_get_cipher(), $this->_get_mode());

		if ($init_size > strlen($data)){
			return false;
		}

		$init_vect = substr($data, 0, $init_size);
		$data = substr($data, $init_size);
		return rtrim(mcrypt_decrypt($this->_get_cipher(), $key, $data, $this->_get_mode(), $init_vect), "\0");
	}

	private function _get_cipher(){
		return MCRYPT_RIJNDAEL_256;
	}

	private function _get_mode(){
		return MCRYPT_MODE_CBC;
	}

	private function _add_cipher_noise($data, $key){
		$keyhash = md5($key);
		$keylen = strlen($keyhash);
		$str = '';

		for($i = 0, $j = 0, $len = strlen($data); $i < $len; ++$i, ++$j){
			if ($j >= $keylen){
				$j = 0;
			}

			$str .= chr((ord($data[$i]) + ord($keyhash[$j])) % 256);
		}

		return $str;
	}

	function _remove_cipher_noise($data, $key){
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