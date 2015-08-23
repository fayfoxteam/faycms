<?php
namespace fay\validators;

use fay\core\Validator;

/**
 * 判断输入是否为日期时间格式，例如：
 * 2015-02-19 22:02:30
 * 前导0可省略
 * 若int为true，则可能是被转为时间戳的时间，此时只要是int类型都会返回true
 */
class Datetime extends Validator{
	public $pattern = '/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/';
	
	public $message = '{$attribute}日期格式不正确';
	
	/**
	 * 因为datetime类型很有可能先被strtotime过
	 * 用户直接输入数字一定是无效的，因为用户提交数据为string类型
	 */
	public $int = false;
	
	public function validate($value){
		if($this->int){
			if(!is_int($value)){
				return $this->message;
			}
		}else{
			if(!preg_match($this->pattern, $value)){
				return $this->message;
			}
		}
		return true;
	}
}