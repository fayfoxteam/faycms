<?php
namespace cms\services\prop\elements;

/**
 * 文本框
 */
class PropElementText extends ElementAbstract{
    /**
     * 获取表单元素名称
     * @return string
     */
    public static function getName(){
        return '文本框';
    }

    /**
     * @see ElementAbstract::getModel()
     */
    protected function getModel(){
        return $this->usage_model->getModel('varchar');
    }
}