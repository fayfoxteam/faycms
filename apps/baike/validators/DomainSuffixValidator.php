<?php
namespace baike\validators;

use fay\core\Validator;

/**
 * 验证输入是否为域名后缀
 */
class DomainSuffixValidator extends Validator{
    /**
     * 域名后缀正则，不建议修改
     */
    public $pattern = '/^(\.[\w\x{4e00}-\x{9fa5}]+)+$/u';
    
    /**
     * 错误描述
     */
    public $message = '{$attribute}非法';
    
    /**
     * 错误码
     */
    public $code = 'invalid-parameter:{$field}:not-a-domain-suffix';
    
    public function validate($value){
        if(preg_match($this->pattern, $value)){
            return true;
        }else{
            return $this->addError($this->message, $this->code);
        }
    }
}