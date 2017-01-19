<?php
namespace fay\helpers;

class NumberHelper{
	/**
	 * 将传入数字格式化为指定长度
	 *  - 若源数据超过指定长度，会把超过部分高位截断
	 *  - 若源数据小于指定长度，会在前面补零
	 * @param int $value 数据（非数字格式会用intval进行强转）
	 * @param int $length 指定长度
	 * @return string
	 */
	public static function toLength($value, $length){
		if(!StringHelper::isInt($value)){
			$value = intval($value);
		}
		
		$value_length = strlen($value);
		if($value_length > $length){
			return substr($value, $value_length - $length);
		}else if($value_length < $length){
			return str_repeat('0', $length - $value_length) . $value;
		}else{
			return (string)$value;
		}
	}
	
	/**
	 * 是否为整数。与系统函数is_int不同，此函数只关心格式是否正确，不关心变量类型。
	 * 此函数不接受"+1"这样的正数写法，也不接受"00", "09"这样不符合正常习惯的前导0写法，"-0", "+0"也都是不行的。
	 * 但如果是int类型的变量，则无法区分是否有前导0或者+。
	 * @param int|string $str
	 * @param bool $natural_number_only 若为true，则只能是正整数或0（自然数），默认为true
	 * @return bool
	 */
	public static function isInt($str, $natural_number_only = true){
		if(!is_string($str) && !is_int($str)){
			//如果不是string或int类型，直接返回false
			return false;
		}
		if($natural_number_only){
			return !!preg_match('/^(0|[1-9]\d*)$/', $str);
		}else{
			return !!preg_match('/^(0|-?[1-9]\d*)$/', $str);
		}
	}
}