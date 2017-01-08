<?php
namespace fay\validators;

use fay\core\Validator;

class EmailValidator extends Validator{
	public $pattern = "/^\\w+([-+.']\\w+)*@\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*$/";
	
	public $message = '{$attribute}格式不正确';
	
	public $code = 'invalid-parameter:{$field}-should-be-a-email';
	
	public function validate($value){
		if(preg_match($this->pattern, $value)){
			return true;
		}else{
			return $this->addError($this->message, $this->code);
		}
	}
}