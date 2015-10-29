<?php
namespace fay\validators;

use fay\core\Validator;

/**
 * 验证输入是否为纯中文
 */
class Chinese extends Validator{
	/**
	 * 中文匹配正则，不建议修改
	 */
	public $pattern = '/^[\x{4e00}-\x{9fa5}]+$/u';//PHP中的中文正则跟js里写法不一样
	
	/**
	 * 错误描述
	 */
	public $message = '{$attribute}必须是中文';
	
	/**
	 * 错误码
	 */
	public $code = 'invalid-parameter:{$field}:must-be-chinese';
	
	public function validate($value){
		if(preg_match($this->pattern, $value)){
			return true;
		}else{
			return $this->addError($this->message, $this->code);
		}
	}
}