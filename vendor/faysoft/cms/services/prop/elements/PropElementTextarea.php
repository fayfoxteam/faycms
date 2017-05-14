<?php
namespace cms\services\prop\elements;

/**
 * 文本框
 */
class PropElementTextarea extends ElementAbstract{
    /**
     * @see ElementAbstract::getModel()
     */
    protected function getModel()
    {
        return $this->usage_model->getModel('text');
    }
}