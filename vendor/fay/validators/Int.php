<?php
namespace fay\validators;

use fay\core\Validator;
use fay\helpers\String as StringHelper;

/**
 * 验证是否为整数
 * 通过更改属性，可以实现范围验证
 */
class Int extends Validator{
	/**
	 * 最小值
	 * @var int
	 */
	public $min;
	
	/**
	 * 最大值
	 * @var int
	 */
	public $max;
	
	public $too_big = '{$attribute}必须是不大于{$max}的整数';
	
	public $too_big_code = 'invalid-parameter:{$field}-is-too-big';
	
	public $too_small = '{$attribute}必须是不小于{$min}的整数';
	
	public $too_small_code = 'invalid-parameter:{$field}-is-too-small';
	
	public $message = '{$attribute}必须是整数';
	
	public $code = 'invalid-parameter:{$field}-should-be-a-number';
	
	public function validate($value){
		if(!StringHelper::isInt($value, false)){
			return $this->addError($this->message, $this->code);
		}
		
		if($this->max !== null && $value > $this->max){
			return $this->addError($this->too_big, $this->too_big_code, array('max'=>$this->max));
		}
		
		if($this->min !== null && $value < $this->min){
			return $this->addError($this->too_small, $this->too_small_code, array('min'=>$this->min));
		}
		
		return true;
	}
}