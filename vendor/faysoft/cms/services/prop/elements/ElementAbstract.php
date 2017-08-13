<?php
namespace cms\services\prop\elements;

use cms\services\prop\PropUsageInterface;
use fay\core\db\Table;

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
     * 获取数据存储表模型
     * @return Table
     */
    abstract protected function getModel();

    /**
     * 获取表单元素名称
     * @throws \ErrorException
     */
    public static function getName(){
        throw new \ErrorException('请在子类中定义名称');
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
        $old_value = $this->getModel()->fetchRow(array(
            'relation_id = ?'=>$relation_id,
            'prop_id = ?'=>$prop_id,
        ), 'content');
        if($old_value){
            //若存在，且值有变化，则更新
            if($old_value['content'] != $value){
                $this->getModel()->update(array(
                    'content'=>$value,
                ), array(
                    'relation_id = ?'=>$relation_id,
                    'prop_id = ?'=>$prop_id,
                ));
            }
        }else{
            //若不存在，则新增
            $this->create($relation_id, $prop_id, $value);
        }
    }

    /**
     * 获取一个属性值
     * @param int $relation_id
     * @param int $prop_id
     * @return string|null
     */
    public function get($relation_id, $prop_id){
        $value = $this->getModel()->fetchRow(array(
            'relation_id = ?'=>$relation_id,
            'prop_id = ?'=>$prop_id,
        ), 'content');
        if($value){
            return $value['content'];
        }else{
            return '';
        }
    }

    /**
     * 新增一个属性值。
     * 在确定是新增的情况下，直接插入，减少数据库操作。
     * 若不确定原先是否存在此属性，可以调用set方法进行设置。
     * @param int $relation_id
     * @param int $prop_id
     * @param string $value
     * @return int
     */
    public function create($relation_id, $prop_id, $value){
        return $this->getModel()->insert(array(
            'relation_id'=>$relation_id,
            'prop_id'=>$prop_id,
            'content'=>$value,
        ));
    }
}