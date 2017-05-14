<?php
namespace cms\services\prop\elements;

use cms\services\prop\PropUsageInterface;

abstract class ElementAbstract{
    /**
     * @var PropUsageInterface 与属性类型相关的信息获取
     */
    protected $usage_model;

    /**
     * @param PropUsageInterface $usage_model
     */
    public function __construct($usage_model){
        $this->usage_model = $usage_model;
    }
    
    /**
     * 设置单个属性值
     * @param int $relation_id
     * @param int $prop_id
     * @param mixed $value
     * @return bool|void
     */
    public function set($relation_id, $prop_id, $value){
        //根据条件先尝试获取属性值
        $old_value = $this->get($relation_id, $prop_id);
        if($old_value !== null){
            //若存在，且值有变化，则更新
            if($old_value != $value){
                $this->update($relation_id, $prop_id, $value);
            }
        }else{
            //若不存在，则新增
            $this->create($relation_id, $prop_id, $value);
        }
    }

    /**
     * 获取一个属性值。若未设置，返回null
     * @param int $relation_id
     * @param int $prop_id
     * @return string|null
     */
    abstract public function get($relation_id, $prop_id);

    /**
     * 新增一个属性值。
     * 在确定是新增的情况下，直接插入，减少数据库操作。
     * 若不确定原先是否存在此属性，可以调用set方法进行设置。
     * @param int $relation_id
     * @param int $prop_id
     * @param mixed $value
     */
    abstract public function create($relation_id, $prop_id, $value);

    /**
     * 修改一个属性值。
     * 此方法不会判断原先是否有值，由set()方法调用，直接进行update操作
     * @param int $relation_id
     * @param int $prop_id
     * @param mixed $value
     */
    abstract protected function update($relation_id, $prop_id, $value);
}