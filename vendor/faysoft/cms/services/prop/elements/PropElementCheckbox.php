<?php
namespace cms\services\prop\elements;

use fay\core\ErrorException;
use fay\helpers\NumberHelper;

/**
 * 多选框
 */
class PropElementCheckbox extends ElementAbstract{
    /**
     * 多选框处理与其他表单元素处理不一样，重写set方法
     * @param int $relation_id
     * @param int $prop_id
     * @param array|int|string $value
     * @return bool|void
     */
    public function set($relation_id, $prop_id, $value){
        $old_values = $this->get($relation_id, $prop_id);
        if($old_values){
            //处理回数组形式
            $old_values = explode(',', $old_values);

            if(empty($value)){
                //若无提交值，且原先有值，则删除以前的值
                if($old_values){
                    $this->usage_model->getModel('int')->delete(array(
                        'relation_id = ?'=>$relation_id,
                        'prop_id = ?'=>$prop_id,
                    ));
                }
            }else{
                $value = $this->formatValue($value);

                //删除已经不存在的项
                $delete_options = array_diff($old_values, $value);
                if($delete_options){
                    $this->usage_model->getModel('int')->delete(array(
                        'relation_id = ?'=>$relation_id,
                        'prop_id = ?'=>$prop_id,
                        'content IN (?)'=>$delete_options,
                    ));
                }

                //插入新增项
                $new_options = array_diff($value, $old_values);
                if($new_options){
                    foreach($new_options as $p_value){
                        $this->usage_model->getModel('int')->insert(array(
                            'relation_id'=>$relation_id,
                            'prop_id'=>$prop_id,
                            'content'=>intval($p_value),
                        ));
                    }
                }
            }
        }else{
            //原先没值，直接插入就好了
            $this->create($relation_id, $prop_id, $value);
        }
    }
    
    /**
     * 获取一个属性值。若未设置，返回null
     * @param int $relation_id
     * @param int $prop_id
     * @return string|null
     */
    public function get($relation_id, $prop_id){
        $values = $this->usage_model->getModel('int')->fetchCol('content', array(
            'relation_id = ?'=>$relation_id,
            'prop_id = ?'=>$prop_id,
        ));
        if($values){
            //为了返回字段类型一致，以逗号分割返回
            return implode(',', $values['content']);
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
     * @param string $value
     * @return array
     */
    public function create($relation_id, $prop_id, $value){
        $value = $this->formatValue($value);

        $return = array();
        foreach($value as $v){
            $return[] = $this->usage_model->getModel('int')->insert(array(
                'relation_id'=>$relation_id,
                'prop_id'=>$prop_id,
                'content'=>intval($v),
            ));
        }
        
        return $return;
    }

    /**
     * 直接在set中写逻辑，不调用update
     * @param int $relation_id
     * @param int $prop_id
     * @param mixed $value
     */
    protected function update($relation_id, $prop_id, $value){
        
    }

    /**
     * 格式化属性值
     * @param mixed $value
     * @return array
     * @throws ErrorException
     */
    private function formatValue($value){
        if(!$value){
            //若是一个空值，返回空数组
            return array();
        }
        if(NumberHelper::isInt($value)){
            $value = array($value);
        }else if(is_string($value)){
            $value = explode(',', str_replace(' ', '', $value));
        }else if(!is_array($value)){
            throw new ErrorException('$value数据格式异常');
        }
        
        return $value;
    }
}