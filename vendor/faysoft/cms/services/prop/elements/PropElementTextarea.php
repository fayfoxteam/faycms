<?php
namespace cms\services\prop\elements;

/**
 * 文本框
 */
class PropElementTextarea extends ElementAbstract{
    /**
     * 获取表单元素名称
     * @return string
     */
    public static function getName(){
        return '文本域';
    }

    /**
     * @see ElementAbstract::getModel()
     */
    protected function getModel()
    {
        return $this->usage_model->getModel('text');
    }
}