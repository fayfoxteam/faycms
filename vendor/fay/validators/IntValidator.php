<?php
namespace fay\validators;

use fay\core\Validator;
use fay\helpers\StringHelper as StringHelper;

/**
 * 验证是否为整数
 * 通过更改属性，可以实现范围验证
 */
class IntValidator extends Validator{
	/**
	 * 若为true，允许传入数组，每个数组项都必须是数字
	 */
	public $allow_array = true;
	
	/**
	 * 若为true，允许逗号分割（但不允许有空格），拆分后每项都必须是符合条件的整数
	 */
	public $allow_comma_separated = true;
	
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
		if($this->allow_array && is_array($value)){
			//如果允许传入数组且传入的是数组
			foreach($value as $v){
				if($this->skip_on_empty && ($v === null || $v === '' || $v === array())){
					//跳过为空的值
					continue;
				}
				$check = $this->checkItem($v);
				if($check !== true){
					return $this->addError($check[0], $check[1], $check[2]);
				}
			}
			
			return true;
		}
		
		if($this->allow_comma_separated){
			//如果允许逗号分割，进行切割（即便传入的是纯数字，切割也没什么影响）
			$value = explode(',', $value);
			foreach($value as $v){
				if($this->skip_on_empty && ($v === null || $v === '' || $v === array())){
					//跳过为空的值
					continue;
				}
				$check = $this->checkItem($v);
				if($check !== true){
					return $this->addError($check[0], $check[1], $check[2]);
				}
			}
				
			return true;
		}else{
			//只允许纯数字
			$check = $this->checkItem($v);
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
		if(!StringHelper::isInt($item, false)){
			return array($this->message, $this->code, array());
		}
		
		if($this->max !== null && $item > $this->max){
			return array($this->too_big, $this->too_big_code, array('max'=>$this->max));
		}
		
		if($this->min !== null && $item < $this->min){
			return array($this->too_small, $this->too_small_code, array('min'=>$this->min));
		}
		
		return true;
	}
}