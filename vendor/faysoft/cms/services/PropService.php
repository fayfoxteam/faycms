<?php
namespace cms\services;

use cms\models\tables\PropsTable;
use cms\models\tables\PropValuesTable;
use fay\core\ErrorException;
use fay\core\Service;

class PropService extends Service{
    /**
     * @param string $class_name
     * @return $this
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }

    /**
     * 创建自定义属性
     * @param array $prop 至少包含title, alias, type字段
     * @param array $values
     * @return int|null
     * @throws ErrorException
     */
    public function create($prop, $values = array()){
        if(empty($prop['title']) || empty($prop['alias']) || empty($prop['type'])){
            throw new ErrorException(__CLASS__ . '::' . __METHOD__ . '() $prop参数异常: ' . json_encode($prop));
        }
        $prop_id = PropsTable::model()->insert(array(
            'title'=>$prop['title'],
            'alias'=>$prop['alias'],
            'type'=>$prop['type'],
            'element'=>empty($prop['element']) ? PropsTable::ELEMENT_TEXT : $prop['element'],
            'required'=>empty($prop['required']) ? 0 : 1,
            'is_show'=>isset($prop['is_show']) ? $prop['is_show'] : 1,
            'create_time'=>\F::app()->current_time,
        ));

        if(in_array($prop['element'], array(
            PropsTable::ELEMENT_RADIO,
            PropsTable::ELEMENT_SELECT,
            PropsTable::ELEMENT_CHECKBOX,
        ))){
            //设置可选属性值
            $i = 0;
            foreach($values as $pv){
                $i++;
                PropValuesTable::model()->insert(array(
                    'prop_id'=>$prop_id,
                    'title'=>$pv,
                    'sort'=>$i,
                ));
            }
        }

        return $prop_id;
    }

    /**
     * 更新属性
     * @param int $prop_id 属性ID
     * @param array $prop 属性参数
     * @param array $values 属性值
     * @param array $ids 原属性ID数组，键值为空表示新增值
     */
    public function update($prop_id, $prop, $values = array(), $ids = array()){
        $old_ids = array_filter($ids);
        $old_ids || $old_ids = array('-1');

        PropsTable::model()->update($prop, $prop_id, true);

        //删除原有但现在没了的属性值
        PropValuesTable::model()->update(array(
            'delete_time'=>\F::app()->current_time,
        ),array(
            'prop_id = ?'=>$prop_id,
            'id NOT IN ('.implode(',', $old_ids).')',
        ));
        //设置属性值
        if(in_array($prop['element'], array(
            PropsTable::ELEMENT_RADIO,
            PropsTable::ELEMENT_SELECT,
            PropsTable::ELEMENT_CHECKBOX,
        ))){//手工录入属性没有属性值
            $i = 0;
            foreach($values as $k => $v){
                $i++;
                if(!empty($ids[$k])){
                    PropValuesTable::model()->update(array(
                        'title'=>$v,
                        'sort'=>$i,
                    ), array(
                        'id = ?'=>$ids[$k],
                    ));
                }else{
                    PropValuesTable::model()->insert(array(
                        'prop_id'=>$prop_id,
                        'title'=>$v,
                        'sort'=>$i,
                    ));
                }
            }
        }
    }

    /**
     * 删除自定义属性
     * @param int $id
     */
    public function delete($id){
        PropsTable::model()->update(array(
            'delete_time'=>\F::app()->current_time,
        ), $id);
    }

    /**
     * 还原自定义属性
     * @param int $id
     */
    public function undelete($id){
        PropsTable::model()->update(array(
            'delete_time'=>0,
        ), $id);
    }

    /**
     * 获取一个属性，若其为可选属性，则同时获取所有可选项
     * @param int $id
     * @return array|bool
     */
    public function get($id){
        $prop = PropsTable::model()->fetchRow(array(
            'id = ?'=>$id,
            'delete_time = 0',
        ));

        if(!$prop) return array();

        if(in_array($prop['element'], array(
            PropsTable::ELEMENT_RADIO,
            PropsTable::ELEMENT_SELECT,
            PropsTable::ELEMENT_CHECKBOX,
        ))){
            $prop['values'] = PropValuesTable::model()->fetchAll(array(
                'prop_id = ?'=>$prop['id'],
                'delete_time = 0',
            ), '*', 'sort');
        }

        return $prop;
    }
}