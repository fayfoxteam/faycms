<?php
namespace cms\services\prop\elements;

/**
 * 下拉框（处理和单选框是一样的）
 */
class PropElementSelect extends PropElementRadio{
    /**
     * 获取表单元素名称
     * @return string
     */
    public static function getName(){
        return '下拉框';
    }
}