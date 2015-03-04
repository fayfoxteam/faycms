<?php
namespace fay\validators;

use fay\core\Validator;

/**
 * 验证输入是否为url，必须包含http或https协议名称。
 * 你也可以自己传入pattern字段修改正则，虽然我们不建议这么干。
 */
class Url extends Validator{
	/**
	 * Url正则，不建议修改
	 */
	public $pattern = '/^(http|https):\/\/\w+.*$/';
	
	/**
	 * 错误描述
	 */
	public $message = '{$attribute}格式不正确';
	
	public function validate($value){
		if(preg_match($this->pattern, $value)){
			return true;
		}else{
			return $this->message;
		}
	}
}