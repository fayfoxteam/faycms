<?php
namespace fay\validators;

use fay\core\Validator;

/**
 * 判断输入是否为日期时间格式，例如：
 * 2015-02-19 22:02:30
 * 前导0可省略
 * 若int为true，则可能是被转为时间戳的时间，此时只要是int类型都会返回true
 */
class DatetimeValidator extends Validator{
    public $pattern = '/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/';
    
    public $message = '{$attribute}日期格式不正确';
    
    public $code = 'invalid-parameter:{$field}-is-not-a-date';
    
    /**
     * 若为true，允许传入数组，每个数组项都必须是日期格式
     */
    public $allow_array = true;
    
    /**
     * 因为datetime类型很有可能先被strtotime过
     * 用户直接输入数字一定是无效的，因为用户提交数据为string类型
     */
    public $int = false;
    
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
                    return $this->addError($check[0], $check[1]);
                }
            }
                
            return true;
        }else{
            $check = $this->checkItem($value);
            if($check !== true){
                return $this->addError($check[0], $check[1]);
            }
            
            return true;
        }
    }
    
    /**
     * 判断一项是否符合标准
     * @param mixed $item
     * @return array|bool
     */
    private function checkItem($item){
        if($this->int && !is_int($item)){
            return array($this->message, $this->code);
        }else if(!preg_match($this->pattern, $item)){
            return array($this->message, $this->code);
        }
        
        return true;
    }
}