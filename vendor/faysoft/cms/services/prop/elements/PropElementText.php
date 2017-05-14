<?php
namespace cms\services\prop\elements;

/**
 * 文本框
 */
class PropElementText extends ElementAbstract{
    /**
     * @see ElementAbstract::getModel()
     */
    protected function getModel(){
        return $this->usage_model->getModel('varchar');
    }
}