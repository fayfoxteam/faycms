<?php
namespace fay\validators;

use fay\core\Validator;

class Range extends Validator{
	public $message = '{$attribute}的取值非法';
	
	public $code = 'invalid-parameter:{$field}:not-in-range';
	
	/**
	 * 范围，这个值必须设置
	 */
	public $range = array();
	
	/**
	 * 若为true，则还会比较变量类型是否一致
	 */
	public $strict = false;
	
	/**
	 * 若为true，则这个验证器做反向操作，即不再范围内的值才为真
	 */
	public $not = false;
	
	public function validate($value){
		if(!$this->not && in_array($value, $this->range, $this->strict)
			|| $this->not && !in_array($value, $this->range, $this->strict)){
			return true;
		}else{
			return $this->addError($this->message, $this->code);
		}
	}
}