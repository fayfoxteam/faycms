<?php
namespace cms\services\prop;

use cms\models\tables\PropsTable;
use fay\core\Sql;
use fay\helpers\ArrayHelper;

/**
 * 单体自定义属性服务
 * 例如：某篇文章的自定义属性设置，获取。
 */
class ItemPropService{
    /**
     * @var PropTypeInterface 与属性类型相关的信息获取
     */
    private $type_model;

    /**
     * @param PropTypeInterface $type_model
     */
    public function __construct($type_model){
        $this->type_model = $type_model;
    }
    
    /**
     * 创建一个属性集
     * @param int $refer $models中对应的字段值
     * @param array $props 属性集合
     * @param array $data 属性值，以属性集合的id为键的数组
     */
    public function createPropertySet($refer, $props, $data){
        foreach($props as $p){
            switch($p['element']){
                case PropsTable::ELEMENT_TEXT:
                    $this->type_model->getModel('varchar')->insert(array(
                        'refer'=>$refer,
                        'prop_id'=>$p['id'],
                        'content'=>$data[$p['id']],
                    ));
                    break;
                case PropsTable::ELEMENT_RADIO:
                    if(isset($data[$p['id']])){
                        $this->type_model->getModel('int')->insert(array(
                            'refer'=>$refer,
                            'prop_id'=>$p['id'],
                            'content'=>intval($data[$p['id']]),
                        ));
                    }
                    break;
                case PropsTable::ELEMENT_SELECT:
                    if(!empty($data[$p['id']])){
                        $this->type_model->getModel('int')->insert(array(
                            'refer'=>$refer,
                            'prop_id'=>$p['id'],
                            'content'=>intval($data[$p['id']]),
                        ));
                    }
                    break;
                case PropsTable::ELEMENT_CHECKBOX:
                    if(isset($data[$p['id']])){
                        foreach($data[$p['id']] as $v){
                            $this->type_model->getModel('int')->insert(array(
                                'refer'=>$refer,
                                'prop_id'=>$p['id'],
                                'content'=>intval($v),
                            ));
                        }
                    }
                    break;
                case PropsTable::ELEMENT_TEXTAREA:
                    if(!empty($data[$p['id']])){
                        $this->type_model->getModel('text')->insert(array(
                            'refer'=>$refer,
                            'prop_id'=>$p['id'],
                            'content'=>$data[$p['id']],
                        ));
                    }
                    break;
                case PropsTable::ELEMENT_NUMBER:
                    if(!empty($data[$p['id']])){
                        $this->type_model->getModel('int')->insert(array(
                            'refer'=>$refer,
                            'prop_id'=>$p['id'],
                            'content'=>intval($data[$p['id']]),
                        ));
                    }
                    break;
                case PropsTable::ELEMENT_IMAGE:
                    if(!empty($data[$p['id']])){
                        $this->type_model->getModel('int')->insert(array(
                            'refer'=>$refer,
                            'prop_id'=>$p['id'],
                            'content'=>intval($data[$p['id']]),
                        ));
                    }
                    break;
            }
        }
    }
    
    /**
     * 获取一个属性集
     * @param $refer
     * @param $props
     * @return array
     */
    public function getPropertySet($refer, $props){
        $property_set = array();
        $sql = new Sql();
        foreach($props as $p){
            switch($p['element']){
                case PropsTable::ELEMENT_TEXT:
                    $value = $this->type_model->getModel('varchar')->fetchRow(array(
                        "{'refer'} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    if($value){
                        $p['value'] = $value['content'];
                    }else{
                        $p['value'] = '';
                    }
                    break;
                case PropsTable::ELEMENT_RADIO:
                    $value = $sql->from(array('pi'=>$this->type_model->getModel('int')->getTableName()), '')
                        ->joinLeft(array('v'=>'prop_values'), 'pi.content = v.id', 'id')
                        ->where(array(
                            "pi.{'refer'} = ?"=>$refer,
                            'pi.prop_id = ?'=>$p['id'],
                        ))
                        ->fetchRow()
                    ;
                    $p['value'] = $value['id'];
                    break;
                case PropsTable::ELEMENT_SELECT:
                    $value = $sql->from(array('pi'=>$this->type_model->getModel('int')->getTableName()), '')
                        ->joinLeft(array('v'=>'prop_values'), 'pi.content = v.id', 'id')
                        ->where(array(
                            "pi.{'refer'} = ?"=>$refer,
                            'pi.prop_id = ?'=>$p['id'],
                        ))
                        ->fetchRow()
                    ;
                    $p['value'] = $value['id'];
                    break;
                case PropsTable::ELEMENT_CHECKBOX:
                    $value = $sql->from(array('pi'=>$this->type_model->getModel('int')->getTableName()), '')
                        ->joinLeft(array('v'=>'prop_values'), 'pi.content = v.id', 'id')
                        ->where(array(
                            "pi.{'refer'} = ?"=>$refer,
                            'pi.prop_id = ?'=>$p['id'],
                        ))
                        ->fetchAll()
                    ;
                    $p['value'] = implode(',', ArrayHelper::column($value, 'id'));
                    break;
                case PropsTable::ELEMENT_TEXTAREA:
                    $value = $this->type_model->getModel('text')->fetchRow(array(
                        "{'refer'} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    if($value){
                        $p['value'] = $value['content'];
                    }else{
                        $p['value'] = '';
                    }
                    break;
                case PropsTable::ELEMENT_NUMBER:
                    $value = $this->type_model->getModel('int')->fetchRow(array(
                        "{'refer'} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    if($value){
                        $p['value'] = $value['content'];
                    }else{
                        $p['value'] = '';
                    }
                    break;
                case PropsTable::ELEMENT_IMAGE:
                    $value = $this->type_model->getModel('int')->fetchRow(array(
                        "{'refer'} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    if($value){
                        $p['value'] = $value['content'];
                    }else{
                        $p['value'] = '0';
                    }
                    break;
            }
            $property_set[] = $p;
        }
        return $property_set;
    }
    
    /**
     * 更新一个属性集
     * @param int $refer 字段值
     * @param array $props 属性集合
     * @param array $data 属性值
     */
    public function updatePropertySet($refer, $props, $data){
        foreach($props as $p){
            switch($p['element']){
                case PropsTable::ELEMENT_TEXT:
                    //如果存在，且值有变化，则更新；不存在，则插入
                    $record = $this->type_model->getModel('varchar')->fetchRow(array(
                        "{'refer'} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    if($record){
                        if($record['content'] != $data[$p['id']]){
                            $this->type_model->getModel('varchar')->update(array(
                                'content'=>$data[$p['id']],
                            ), array(
                                "{'refer'} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                            ));
                        }
                    }else{
                        $this->type_model->getModel('varchar')->insert(array(
                            'refer'=>$refer,
                            'prop_id'=>$p['id'],
                            'content'=>$data[$p['id']],
                        ));
                    }
                    break;
                case PropsTable::ELEMENT_RADIO:
                    $record = $this->type_model->getModel('int')->fetchRow(array(
                        "{'refer'} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    if(empty($data[$p['id']])){
                        //若无提交值，且原先有值，则删除以前的值
                        if($record){
                            $this->type_model->getModel('int')->delete(array(
                                "{'refer'} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                            ));
                        }
                    }else{
                        //如果存在，且值有变化，则更新；不存在，则插入
                        if($record){
                            if($record['content'] != $data[$p['id']]){
                                $this->type_model->getModel('int')->update(array(
                                    'content'=>intval($data[$p['id']]),
                                ), array(
                                    "{'refer'} = ?"=>$refer,
                                    'prop_id = ?'=>$p['id'],
                                ));
                            }
                        }else{
                            $this->type_model->getModel('int')->insert(array(
                                'refer'=>$refer,
                                'prop_id'=>$p['id'],
                                'content'=>intval($data[$p['id']]),
                            ));
                        }
                    }
                    break;
                case PropsTable::ELEMENT_SELECT:
                    $record = $this->type_model->getModel('int')->fetchRow(array(
                        "{'refer'} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    if(empty($data[$p['id']])){
                        //若无提交值，且原先有值，则删除以前的值
                        if($record){
                            $this->type_model->getModel('int')->delete(array(
                                "{'refer'} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                            ));
                        }
                    }else{
                        //如果存在，且值有变化，则更新；不存在，则插入
                        if($record){
                            if($record['content'] != $data[$p['id']]){
                                $this->type_model->getModel('int')->update(array(
                                    'content'=>intval($data[$p['id']]),
                                ), array(
                                    "{'refer'} = ?"=>$refer,
                                    'prop_id = ?'=>$p['id'],
                                ));
                            }
                        }else{
                            $this->type_model->getModel('int')->insert(array(
                                'refer'=>$refer,
                                'prop_id'=>$p['id'],
                                'content'=>intval($data[$p['id']]),
                            ));
                        }
                    }
                    break;
                case PropsTable::ELEMENT_CHECKBOX:
                    //获取已存在的项
                    $old_options = $this->type_model->getModel('int')->fetchCol('content', array(
                        "{'refer'} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ));
                    if(isset($data[$p['id']])){
                        //删除已经不存在的项
                        $delete_options = array_diff($old_options, $data[$p['id']]);
                        if($delete_options){
                            $this->type_model->getModel('int')->delete(array(
                                "{'refer'} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                                'content IN (?)'=>$delete_options,
                            ));
                        }
                        
                        //插入新增项
                        $new_options = array_diff($data[$p['id']], $old_options);
                        if($new_options){
                            foreach($new_options as $p_value){
                                $this->type_model->getModel('int')->insert(array(
                                    'refer'=>$refer,
                                    'prop_id'=>$p['id'],
                                    'content'=>intval($p_value),
                                ));
                            }
                        }
                    }else{
                        //若无提交值，且原先有值，则删除以前的值
                        if($old_options){
                            $this->type_model->getModel('int')->delete(array(
                                "{'refer'} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                            ));
                        }
                    }
                    break;
                case PropsTable::ELEMENT_TEXTAREA:
                    $record = $this->type_model->getModel('text')->fetchRow(array(
                        "{'refer'} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    //如果存在，且值有变化，则更新；不存在，则插入
                    if($record){
                        if($record['content'] != $data[$p['id']]){
                            $this->type_model->getModel('text')->update(array(
                                'content'=>$data[$p['id']],
                            ), array(
                                "{'refer'} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                            ));
                        }
                    }else{
                        $this->type_model->getModel('text')->insert(array(
                            'refer'=>$refer,
                            'prop_id'=>$p['id'],
                            'content'=>$data[$p['id']],
                        ));
                    }
                    break;
                case PropsTable::ELEMENT_NUMBER:
                    //如果存在，且值有变化，则更新；不存在，则插入
                    $record = $this->type_model->getModel('int')->fetchRow(array(
                        "{'refer'} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    if($record){
                        if($record['content'] != $data[$p['id']]){
                            $this->type_model->getModel('int')->update(array(
                                'content'=>$data[$p['id']],
                            ), array(
                                "{'refer'} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                            ));
                        }
                    }else{
                        $this->type_model->getModel('int')->insert(array(
                            'refer'=>$refer,
                            'prop_id'=>$p['id'],
                            'content'=>intval($data[$p['id']]),
                        ));
                    }
                    break;
                case PropsTable::ELEMENT_IMAGE:
                    if(empty($data[$p['id']])){
                        //若没有传值过来或传了空值，且原先有记录，则删除记录
                        if($this->type_model->getModel('int')->fetchRow(array(
                            "{'refer'} = ?"=>$refer,
                            'prop_id = ?'=>$p['id'],
                        ), 'content')){
                            $this->type_model->getModel('int')->delete(array(
                                "{'refer'} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                            ));
                        }
                    }else{
                        //如果存在，且值有变化，则更新；不存在，则插入
                        $record = $this->type_model->getModel('int')->fetchRow(array(
                            "{'refer'} = ?"=>$refer,
                            'prop_id = ?'=>$p['id'],
                        ), 'content');
                        if($record){
                            if($record['content'] != $data[$p['id']]){
                                $this->type_model->getModel('int')->update(array(
                                    'content'=>$data[$p['id']],
                                ), array(
                                    "{'refer'} = ?"=>$refer,
                                    'prop_id = ?'=>$p['id'],
                                ));
                            }
                        }else{
                            $this->type_model->getModel('int')->insert(array(
                                'refer'=>$refer,
                                'prop_id'=>$p['id'],
                                'content'=>$data[$p['id']],
                            ));
                        }
                    }
                    break;
            }
        }
    }
    
    /**
     * 根据属性别名，单一更新一个属性的属性值
     * @param string $alias 属性别名
     * @param mixed $value 属性值
     * 若属性元素对应的是输入框，文本域或单选框，则直接更新属性值
     * 若属性元素对应的是多选框：
     *  - 当$value是数字的时候，仅做插入（已存在则无操作）操作，
     *  - 当$value是数组的时候，将影响原有的属性值（不存在则删除，已存在则无操作）
     * @param int $refer 引用值（例如：文章ID，用户ID）
     * @return bool
     */
    public function setValue($alias, $value, $refer){
        $prop = PropsTable::model()->fetchRow(array(
            'alias = ?'=>$alias,
        ), 'id,element');
        if(!$prop) return false;
        
        switch($prop['element']){
            case PropsTable::ELEMENT_RADIO:
            case PropsTable::ELEMENT_SELECT:
                $record = $this->type_model->getModel('int')->fetchRow(array(
                    "{'refer'} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ));
                if($record){
                    if($record['content'] != $value){
                        $this->type_model->getModel('int')->update(array(
                            'content'=>intval($value),
                        ), array(
                            "{'refer'} = ?"=>$refer,
                            'prop_id = ?'=>$prop['id'],
                        ));
                    }
                }else{
                    $this->type_model->getModel('int')->insert(array(
                        'refer'=>$refer,
                        'prop_id'=>$prop['id'],
                        'content'=>intval($value),
                    ));
                }
                break;
            case PropsTable::ELEMENT_CHECKBOX:
                if(is_array($value)){//$value是数组，完整更新
                    //删除已经不存在的项
                    $this->type_model->getModel('int')->delete(array(
                        "{'refer'} = ?"=>$refer,
                        'prop_id = ?'=>$prop['id'],
                        'content NOT IN ('.implode(',', \F::filter('intval', $value)).')',
                    ));
                    //获取已存在的项
                    $old_options = $this->type_model->getModel('int')->fetchCol('content', array(
                        "{'refer'} = ?"=>$refer,
                        'prop_id = ?'=>$prop['id'],
                    ));
                    //插入新增项
                    foreach($value as $p_value){
                        if(!in_array($p_value, $old_options)){
                            $this->type_model->getModel('int')->insert(array(
                                'refer'=>$refer,
                                'prop_id'=>$prop['id'],
                                'content'=>intval($p_value),
                            ));
                        }
                    }
                }else{//$value不是数组，仅更新一个属性值选项
                    if($this->type_model->getModel('int')->fetchRow(array(
                        "{'refer'} = ?"=>$refer,
                        'prop_id = ?'=>$prop['id'],
                    ))){
                        $this->type_model->getModel('int')->update(array(
                            'content'=>intval($value),
                        ), array(
                            "{'refer'} = ?"=>$refer,
                            'prop_id = ?'=>$prop['id'],
                        ));
                    }else{
                        $this->type_model->getModel('int')->insert(array(
                            'refer'=>$refer,
                            'prop_id'=>$prop['id'],
                            'content'=>intval($value),
                        ));
                    }
                }
                break;
            case PropsTable::ELEMENT_TEXT:
                //如果存在，且值有变化，则更新；不存在，则插入
                $record = $this->type_model->getModel('text')->fetchRow(array(
                    "{'refer'} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ));
                if($record){
                    if($record['content'] != $value){
                        $this->type_model->getModel('varchar')->update(array(
                            'content' => $value,
                        ), array(
                            "{'refer'} = ?" => $refer,
                            'prop_id = ?' => $prop['id'],
                        ));
                    }
                }else{
                    $this->type_model->getModel('varchar')->insert(array(
                        'refer'=>$refer,
                        'prop_id'=>$prop['id'],
                        'content'=>$value,
                    ));
                }
                break;
            case PropsTable::ELEMENT_TEXTAREA:
                //如果存在，且值有变化，则更新；不存在，则插入
                $record = $this->type_model->getModel('text')->fetchRow(array(
                    "{'refer'} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ));
                if($record){
                    if($record['content'] != $value){
                        $this->type_model->getModel('text')->update(array(
                            'content' => $value,
                        ), array(
                            "{'refer'} = ?" => $refer,
                            'prop_id = ?' => $prop['id'],
                        ));
                    }
                }else{
                    $this->type_model->getModel('text')->insert(array(
                        'refer'=>$refer,
                        'prop_id'=>$prop['id'],
                        'content'=>$value,
                    ));
                }
                break;
            case PropsTable::ELEMENT_NUMBER:
                //如果存在，且值有变化，则更新；不存在，则插入
                $record = $this->type_model->getModel('int')->fetchRow(array(
                    "{'refer'} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ));
                if($record){
                    if($record['content'] != $value){
                        $this->type_model->getModel('int')->update(array(
                            'content'=>$value,
                        ), array(
                            "{'refer'} = ?"=>$refer,
                            'prop_id = ?'=>$prop['id'],
                        ));
                    }
                }else{
                    $this->type_model->getModel('int')->insert(array(
                        'refer'=>$refer,
                        'prop_id'=>$prop['id'],
                        'content'=>$value,
                    ));
                }
                break;
            case PropsTable::ELEMENT_IMAGE:
                //如果存在，且值有变化，则更新；不存在，则插入
                $record = $this->type_model->getModel('int')->fetchRow(array(
                    "{'refer'} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ));
                if($record){
                    if($record['content'] != $value){
                        $this->type_model->getModel('int')->update(array(
                            'content'=>$value,
                        ), array(
                            "{'refer'} = ?"=>$refer,
                            'prop_id = ?'=>$prop['id'],
                        ));
                    }
                }else{
                    $this->type_model->getModel('int')->insert(array(
                        'refer'=>$refer,
                        'prop_id'=>$prop['id'],
                        'content'=>$value,
                    ));
                }
                break;
        }
        
        return true;
    }
    
    /**
     * 获取一个用户属性值
     * @param string $alias
     * @param int $refer 引用值（例如：文章ID，用户ID）
     * @return mixed
     */
    public function getValue($alias, $refer){
        $prop = PropsTable::model()->fetchRow(array(
            'alias = ?'=>$alias,
        ), 'id,element');
        if(!$prop) return false;
        
        $sql = new Sql();
        switch($prop['element']){
            case PropsTable::ELEMENT_TEXT:
                $value = $this->type_model->getModel('varchar')->fetchRow(array(
                    "{'refer'} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ), 'content');
                if($value){
                    return $value['content'];
                }else{
                    return '';
                }
            case PropsTable::ELEMENT_RADIO:
                return $sql->from(array('pi'=>$this->type_model->getModel('int')->getTableName()), '')
                    ->joinLeft(array('v'=>'prop_values'), 'pi.content = v.id', 'id,title')
                    ->where(array(
                        "pi.{'refer'} = ?"=>$refer,
                        'pi.prop_id = ?'=>$prop['id'],
                    ))
                    ->fetchRow()
                ;
            case PropsTable::ELEMENT_SELECT:
            case PropsTable::ELEMENT_CHECKBOX:
                return $sql->from(array('pi'=>$this->type_model->getModel('int')->getTableName()), '')
                    ->joinLeft(array('v'=>'prop_values'), 'pi.content = v.id', 'id,title')
                    ->where(array(
                        "pi.{'refer'} = ?"=>$refer,
                        'pi.prop_id = ?'=>$prop['id'],
                    ))
                    ->fetchAll()
                ;
            case PropsTable::ELEMENT_TEXTAREA:
                $value = $this->type_model->getModel('text')->fetchRow(array(
                    "{'refer'} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ), 'content');
                if($value){
                    return $value['content'];
                }else{
                    return '';
                }
            case PropsTable::ELEMENT_NUMBER:
                $value = $this->type_model->getModel('int')->fetchRow(array(
                    "{'refer'} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ), 'content');
                if($value){
                    return $value['content'];
                }else{
                    return '';
                }
            case PropsTable::ELEMENT_IMAGE:
                $value = $this->type_model->getModel('int')->fetchRow(array(
                    "{'refer'} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ), 'content');
                if($value){
                    return $value['content'];
                }else{
                    return '0';
                }
            default:
                return '';
        }
    }
    
    
}