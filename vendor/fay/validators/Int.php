<?php
namespace fay\validators;

use fay\core\Validator;

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
	
	public $too_small = '{$attribute}必须是不小于{$min}的整数';
	
	public $message = '{$attribute}必须是整数';
	
	public function validate($value){
		if(!is_numeric($value)){
			return $this->addError($this->_field, 'int', $this->message);
		}
		
		if($this->max && $value > $this->max){
			return $this->addError($this->_field, 'int', $this->too_big, array('max'=>$this->max));
		}
		
		if($this->min && $value < $this->min){
			return $this->addError($this->_field, 'int', $this->too_small, array('min'=>$this->min));
		}
		
		return true;
	}
}