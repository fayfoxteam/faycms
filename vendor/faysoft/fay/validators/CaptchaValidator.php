<?php
namespace fay\validators;

use fay\core\Validator;
use fay\services\CaptchaService;

/**
 * 验证码
 */
class CaptchaValidator extends Validator{
    /**
     * @see fay\core\Validator::$skip_on_empty
     */
    public $skip_on_empty = false;
    
    /**
     * 错误描述
     */
    public $message = '{$attribute}不正确';
    
    /**
     * 错误码
     */
    public $code = 'invalid-parameter:{$field}:not-match';
    
    public function validate($value){
        if(CaptchaService::check($value)){
            return true;
        }else{
            return $this->addError($this->message, $this->code);
        }
    }
}