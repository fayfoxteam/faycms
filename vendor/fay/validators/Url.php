<?php
namespace fay\validators;

use fay\core\Validator;

/**
 * 验证输入是否为url，必须包含http或https协议名称。
 * 你也可以自己传入pattern字段修改正则，虽然我们不建议这么干。
 */
class Url extends Validator{
	/**
	 * 若为true，允许传入数组，每个数组项都必须是数字
	 */
	public $allow_array = true;
	
	/**
	 * Url正则，不建议修改
	 */
	public $pattern = '/^(http|https):\/\/\w+.*$/';
	
	public $code = 'invalid-parameter:{$field}-should-be-a-url';
	
	/**
	 * 错误描述
	 */
	public $message = '{$attribute}格式不正确';
	
	public function validate($value){
		if($this->allow_array && is_array($value)){
			//如果允许传入数组且传入的是数组
			foreach($value as $v){
				$check = $this->checkItem($v);
				if($check !== true){
					return $this->addError($check[0], $check[1], $check[2]);
				}
			}
			
			return true;
		}else{
			$check = $this->checkItem($value);
			if($check !== true){
				return $this->addError($check[0], $check[1], $check[2]);
			}
				
			return true;
		}
	}
	
	/**
	 * 判断一个一项是否符合标准
	 * @param int $item
	 */
	private function checkItem($item){
		if(is_string($item) && preg_match($this->pattern, $item)){
			return true;
		}else{
			return array($this->message, $this->code, array());
		}
	}
}