<?php
namespace fay\validators;

use fay\core\Validator;

/**
 * 验证身份证格式是否正确（仅支持18位身份证）
 */
class IDCardValidator extends Validator{
    /**
     * 错误描述
     */
    public $message = '{$attribute}格式不正确';
    
    /**
     * 错误码
     */
    public $code = 'invalid-parameter:{$field}:format-error';
    
    public function validate($value){
        if(self::isValidityBirthBy18IdCard($value) && self::isTrueValidateCodeBy18IdCard(str_split($value))){
            return true;
        }else{
            return $this->addError($this->message, $this->code);
        }
    }
    
    private static function isTrueValidateCodeBy18IdCard($value){
        $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1);// 加权因子;
        $valid_code = array(1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2);// 身份证验证位值，10代表X;
        $sum = 0; // 声明加权求和变量
        if(strtolower($value[17]) == 'x'){
            $value[17] = 10;// 将最后位为x的验证码替换为10方便后续操作
        }
        for( $i = 0; $i < 17; $i++){
            $sum += $wi[$i] * $value[$i];// 加权求和
        }
        $val_code_position = $sum % 11;// 得到验证码所位置
        if($value[17] == $valid_code[$val_code_position]){
            return true;
        }
        return false;
    }
    
    private static function isValidityBirthBy18IdCard($idCard18){
        return date('Ymd', strtotime(substr($idCard18, 6, 8))) == substr($idCard18, 6, 8);
    }
}