<?php
namespace fay\core;

class Model{
    /**
     * 返回验证规则
     * @return array
     */
    public function rules(){
        return array();
    }
    
    /**
     * 返回字段描述
     * @return array
     */
    public function labels(){
        return array();
    }
    
    /**
     * 返回验证器
     * @return array
     */
    public function filters(){
        return array();
    }
}