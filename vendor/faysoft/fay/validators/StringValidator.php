<?php
namespace fay\validators;

use fay\core\Validator;

/**
 * 字符串验证
 */
class StringValidator extends Validator{
	/**
	 * 若为true，允许传入数组，每个数组项都必须是数字
	 */
	public $allow_array = true;
	
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
	
	public $too_long_code = 'invalid-parameter:{$field}:string-too-lange';
	
	public $too_short = '{$attribute}不能少于{$min}个字符';
	
	public $too_short_code = 'invalid-parameter:{$field}:string-too-short';
	
	public $not_equal = '{$attribute}长度必须为{$equal}个字符';
	
	public $not_equal_code = 'invalid-parameter:{$field}:string-not-equal-{$equal}';
	
	public $format_error = '{$attribute}格式不正确';
	
	public $format_error_code = 'invalid-parameter:{$field}:format-error';
	
	/**
	 * 若指定为formats中的key，则调用formats中的正则
	 * 否则将指定的format值视为正则进行格式匹配
	 */
	public $format;
	
	/**
	 * 内置一些格式正则
	 */
	private $formats = array(
		'alias'=>'/^[a-zA-Z][a-zA-Z_0-9-]{0,49}$/',//字母开头，不包含数字，字母，下划线和中横线以外的特殊字符
		'numeric'=>'/^\d+$/',//纯数字
		'alnum'=>'/^[a-zA-Z0-9]+$/',//数字+字母
		'alias_space'=>'/^[a-zA-Z_0-9- ]+$/',//数字，字母，下划线，中横线和空格
	);
	
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
		
		//只允许纯字符串
		$check = $this->checkItem($value);
		if($check !== true){
			return $this->addError($check[0], $check[1], $check[2]);
		}
			
		return true;
	}
	
	public function checkItem($value){
		if($this->format){
			if(isset($this->formats[$this->format])){
				$pattern = $this->formats[$this->format];
			}else{
				$pattern = $this->format;
			}
			if(!preg_match($pattern, $value)){
				return array($this->format_error, $this->format_error_code, array());
			}
		}
		
		$len = mb_strlen($value, 'utf-8');
		
		if($this->equal && $len != $this->equal){
			return array($this->not_equal, $this->not_equal_code, array(
				'equal'=>$this->equal,
			));
		}else if(!$this->equal){
			if($this->max !== null && $len > $this->max){
				return array($this->too_long, $this->too_long_code, array(
					'max'=>$this->max,
				));
			}
	
			if($this->min !== null && $len < $this->min){
				return array($this->too_short, $this->too_short_code, array(
					'min'=>$this->min,
				));
			}
		}
		
		return true;
	}
}