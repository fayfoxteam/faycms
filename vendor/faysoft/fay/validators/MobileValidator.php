<?php
namespace fay\validators;

use fay\core\Validator;

/**
 * 手机号码格式验证
 */
class MobileValidator extends Validator{
	public $pattern = '/^1[0-9]{10}$/';
	
	public $message = '{$attribute}格式不正确';
	
	public function validate($value){
		if(preg_match($this->pattern, $value)){
			return true;
		}else{
			return $this->addError($this->message, $this->code);
		}
	}
}