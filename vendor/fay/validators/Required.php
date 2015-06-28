<?php
namespace fay\validators;

use fay\core\Validator;

/**
 * 非空验证
 */
class Required extends Validator{
	public $message = '{$attribute}不能为空';
	
	public $skip_on_empty = false;
	
	/**
	 * 是否允许空字符串
	 */
	public $enable_empty = false;
	
	public function validate($value){
		if($this->enable_empty){
			//只要有提交，即便是空字符串，也通过验证
			if($value === null){
				return $this->message;
			}else{
				return true;
			}
		}else{
			if(empty($value)){
				return $this->message;
			}else{
				return true;
			}
		}
	}
}