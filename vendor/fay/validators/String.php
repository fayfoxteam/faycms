<?php
namespace fay\validators;

use fay\core\Validator;

/**
 * 字符串验证
 */
class String extends Validator{
	/**
	 * 最大长度
	 */
	public $max;
	
	/**
	 * 最小长度
	 */
	public $min;
	
	/**
	 * 定长，若设置了equal参数，则min和max参数无效
	 */
	public $equal;
	
	public $too_long = '{$attribute}不能超过{$max}个字符';
	
	public $too_short = '{$attribute}不能少于{$min}个字符';
	
	public $not_equal = '{$attribute}长度必须为{$equal}个字符';
	
	public $format_error = '{$attribute}格式不正确';
	
	/**
	 * 若指定为formats中的key，则调用formats中的正则
	 * 否则将指定的format值视为正则进行格式匹配
	 */
	public $format;
	
	/**
	 * 内置一些格式正则
	 */
	private $formats = array(
		'alias'=>'/^[a-zA-Z_0-9-]+$/',//数字，字母，下划线和中横线
		'numeric'=>'/^\d+$/',//纯数字
		'alnum'=>'/^[a-zA-Z0-9]+$/',//数字+字母
		'alias_space'=>'/^[a-zA-Z_0-9- ]+$/',//数字，字母，下划线，中横线和空格
	);
	
	public function validate($value){
		if($this->format){
			if(isset($this->formats[$this->format])){
				$pattern = $this->formats[$this->format];
			}else{
				$pattern = $this->format;
			}
			if(!preg_match($pattern, $value)){
				return $this->format_error;
			}
		}
		
		$len = mb_strlen($value, 'utf-8');
		
		if($this->equal && $len != $this->equal){
			return $this->addError($this->_field, 'string', $this->not_equal, array(
				'equal'=>$this->equal,
			));
		}
		
		if($this->max !== null && $len > $this->max){
			return $this->addError($this->_field, 'string', $this->too_long, array(
				'max'=>$this->max,
			));
		}

		if($this->min !== null && $len < $this->min){
			return $this->addError($this->_field, 'string', $this->too_short, array(
				'min'=>$this->min,
			));
		}
		
		return true;
	}
}