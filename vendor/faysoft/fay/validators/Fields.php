<?php
namespace fay\validators;

use fay\core\Validator;

/**
 * 验证输入fields字段格式是否正确
 * fields是本系统用于指定返回字段的自定义数据格式
 */
class Fields extends Validator{
	/**
	 * 匹配如：id,content,publish_time,user.nickname,user.avatar:200x200,user.roles.*格式的fields字段
	 */
	public $pattern = '/^([\w_]+)(\.[\w_\*]+)*(:[\w_]+)*(,([\w_]+)(\.[\w_\*]+)*(:[\w_]+)*)*$/';
	
	/**
	 * 错误描述
	 */
	public $message = '{$attribute}格式不正确';
	
	/**
	 * 错误码
	 */
	public $code = 'invalid-parameter:{$field}:format-error';
	
	public function validate($value){
		if(preg_match($this->pattern, $value)){
			return true;
		}else{
			return $this->addError($this->message, $this->code);
		}
	}
}