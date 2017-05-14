<?php
namespace cms\services\prop\elements;

/**
 * 单选框
 */
class PropElementRadio extends ElementAbstract{
    /**
     * 获取一个属性值。若未设置，返回null
     * @param int $relation_id
     * @param int $prop_id
     * @return string|null
     */
    public function get($relation_id, $prop_id){
        $value = $this->usage_model->getModel('int')->fetchRow(array(
            'relation_id = ?'=>$relation_id,
            'prop_id = ?'=>$prop_id,
        ), 'content');
        if($value){
            return $value['content'];
        }else{
            return null;
        }
    }

    /**
     * 新增一个属性值。
     * 在确定是新增的情况下，直接插入，减少数据库操作。
     * 若不确定原先是否存在此属性，可以调用set方法进行设置。
     * @param int $relation_id
     * @param int $prop_id
     * @param int $value
     * @return int
     */
    public function create($relation_id, $prop_id, $value){
        return $this->usage_model->getModel('int')->insert(array(
            'relation_id'=>$relation_id,
            'prop_id'=>$prop_id,
            'content'=>intval($value),
        ));
    }

    /**
     * 修改一个属性值
     * 此方法不会判断原先是否有值，由set()方法调用，直接进行update操作
     * @param int $relation_id
     * @param int $prop_id
     * @param int $value
     */
    protected function update($relation_id, $prop_id, $value){
        $this->usage_model->getModel('int')->update(array(
            'content'=>$value,
        ), array(
            'relation_id = ?'=>$relation_id,
            'prop_id = ?'=>$prop_id,
        ));
    }
}