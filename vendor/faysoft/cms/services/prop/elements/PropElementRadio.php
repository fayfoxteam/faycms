<?php
namespace cms\services\prop\elements;

/**
 * 单选框
 */
class PropElementRadio extends ElementAbstract{
    /**
     * @see ElementAbstract::getModel()
     */
    protected function getModel(){
        return $this->usage_model->getModel('int');
    }

    /**
     * 加了个值的类型转换
     * @see ElementAbstract::create()
     */
    public function create($relation_id, $prop_id, $value){
        return $this->usage_model->getModel('int')->insert(array(
            'relation_id'=>$relation_id,
            'prop_id'=>$prop_id,
            'content'=>intval($value),//类型强转一下
        ));
    }
}