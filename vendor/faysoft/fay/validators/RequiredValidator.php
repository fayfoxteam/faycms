<?php
namespace fay\validators;

use fay\core\Validator;

/**
 * 非空验证
 */
class RequiredValidator extends Validator{
	public $message = '{$attribute}不能为空';
	
	public $code = 'missing-parameter:{$field}';
	
	public $skip_on_empty = false;
	
	/**
	 * 是否允许空字符串
	 */
	public $enable_empty = false;
	
	public function validate($value){
		if($this->enable_empty){
			//只要有提交，即便是空字符串，也通过验证
			if($value === null){
				return $this->addError($this->message, $this->code);
			}else{
				return true;
			}
		}else{
			if(empty($value) && $value !== 0 && $value !== '0'){//字符串和数字的0在empty中都返回true
				return $this->addError($this->message, $this->code);
			}else{
				return true;
			}
		}
	}
}