<?php
namespace fay\models;

use cms\models\tables\PropsRefersTable;
use fay\core\ErrorException;
use fay\core\Model;
use cms\models\tables\PropsTable;
use cms\models\tables\PropValuesTable;
use fay\core\Sql;
use fay\helpers\StringHelper;
use fay\helpers\ArrayHelper;

abstract class PropModel extends Model{
    /**
     * 表模型，需要包含int，varchar，text3种类型
     * 此类表必须包含3个字段：{$this->foreign_key}, prop_id, content
     * 其中content字段类型分别为：int(10), varchar(255), text
     * @var array
     */
    protected $models;
    
    /**
     * $this->models中表的外主键（例如文章附加属性，则对应外主键是文章ID：post_id）
     * @var string
     */
    protected $foreign_key;
    
    /**
     * 类型
     * @var int
     */
    protected $type;
    
    public function __construct(){
        if(!$this->models){
            throw new ErrorException(__CLASS__ . '::$models属性未指定');
        }
        if(!$this->foreign_key){
            throw new ErrorException(__CLASS__ . '::$foreign_key属性未指定');
        }
        if(!$this->type){
            throw new ErrorException(__CLASS__ . '::$type属性未指定');
        }
    }

    /**
     * 根据引用（例如：文章分类ID，用户角色ID）获取多个属性
     * @param int|array $refer 引用ID或引用ID构成的一维数组（若$refer为空而$parent_refers非空，不会进行搜索，直接返回空数组）
     * @param array $parent_refers 非直接引用，仅搜索is_share为1的属性
     * @param bool $with_values 若为true，则附加属性可选值。默认为true
     * @return array
     */
    public function getByRefer($refer, array $parent_refers = array(), $with_values = true){
        if(StringHelper::isInt($refer)){
            $conditions = array(
                'refer = ?'=>$refer,
            );
        }else if($refer){
            $conditions = array(
                'refer IN (?)'=>$refer
            );
        }else{
            return array();
        }
        
        if($parent_refers){
            $conditions = array(
                'or'=>array(
                    'and'=>$conditions,
                    'And'=>array(
                        'refer IN (?)'=>$parent_refers,
                        'is_share = 1',
                    )
                )
            );
        }
        
        //这个搜索没有限定type，所以实际上会搜出其他type id重复的记录，但是后面的mget限定了type，所以最终结果并不会错
        //如果在这一步就要判断type的话，就需要连表了，感觉还是现在这样处理更高效一些
        $prop_ids = PropsRefersTable::model()->fetchCol('prop_id', $conditions, 'sort,id');
        
        return $this->mget($prop_ids, $with_values, true);
    }

    /**
     * 获取一个或多个别名对应的属性
     * @param array|string $values 属性别名或ID构成的一维数组或逗号分割字符串
     *  以第一项为判断依据
     *   - 若第一项是数字，视为id
     *   - 若第一项不是数字，视为别名
     * @param bool $with_values 若为true，则附加属性可选值。默认为true
     * @param bool $sort 是否根据$values顺序返回结果
     * @return array
     */
    public function mget($values, $with_values = true, $sort = false){
        if(!$values){
            return array();
        }
        if(!is_array($values)){
            $values = explode(',', $values);
        }
        
        if(StringHelper::isInt($values[0])){
            $field = 'id';
        }else{
            $field = 'alias';
        }
        
        if(isset($values[1])){
            //如果有多项，搜索条件用IN
            $props = PropsTable::model()->fetchAll(array(
                "{$field} IN (?)"=>$values,
                'type = ' . $this->type,
                'delete_time = 0',
            ), 'id,title,type,required,element,alias');
        }else{
            //如果只有一项，搜索条件直接用等于
            $props = PropsTable::model()->fetchAll(array(
                "{$field} = ?"=>$values[0],
                'type = ' . $this->type,
                'delete_time = 0',
            ), 'id,title,type,required,element,alias');
        }
        
        if($with_values && $props){
            //附加属性可选值
            $this->assembleValues($props);
        }
        
        //根据$values传入顺序排序
        if($sort){
            $prop_map = ArrayHelper::column($props, null, $field);
            $props = array();
            foreach($values as $value){
                if(isset($prop_map[$value])){
                    $props[] = $prop_map[$value];
                }
            }
        }
        
        return $props;
    }
    
    /**
     * 为props附加可选值
     * @param array $props
     * @return array
     */
    private function assembleValues(&$props){
        //获取属性对应的可选属性值
        $prop_ids = ArrayHelper::column($props, 'id');
        if(isset($prop_ids[1])){
            $prop_values = PropValuesTable::model()->fetchAll(array(
                'prop_id IN (?)'=>$prop_ids,
                'delete_time = 0',
            ), 'id,title,prop_id', 'prop_id,sort');
        }else{
            $prop_values = PropValuesTable::model()->fetchAll(array(
                'prop_id = ?'=>$prop_ids,
                'delete_time = 0',
            ), 'id,title,prop_id', 'prop_id,sort');
        }
        foreach($props as &$p){
            //保证各项返回数据字段一致性，没有选项的输入类型也返回空的options
            $p['options'] = array();
            if(in_array($p['element'], array(
                PropsTable::ELEMENT_RADIO,
                PropsTable::ELEMENT_SELECT,
                PropsTable::ELEMENT_CHECKBOX,
            ))){
                $start = false;
                foreach($prop_values as $k => $v){
                    if($v['prop_id'] == $p['id']){
                        $p['options'][] = array(
                            'id'=>$v['id'],
                            'title'=>$v['title'],
                        );
                        $start = true;
                        unset($prop_values[$k]);
                    }else if($start){
                        break;
                    }
                }
            }
        }
        
        return $props;
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
                    \F::table($this->models['varchar'])->insert(array(
                        $this->foreign_key=>$refer,
                        'prop_id'=>$p['id'],
                        'content'=>$data[$p['id']],
                    ));
                    break;
                case PropsTable::ELEMENT_RADIO:
                    if(isset($data[$p['id']])){
                        \F::table($this->models['int'])->insert(array(
                            $this->foreign_key=>$refer,
                            'prop_id'=>$p['id'],
                            'content'=>intval($data[$p['id']]),
                        ));
                    }
                    break;
                case PropsTable::ELEMENT_SELECT:
                    if(!empty($data[$p['id']])){
                        \F::table($this->models['int'])->insert(array(
                            $this->foreign_key=>$refer,
                            'prop_id'=>$p['id'],
                            'content'=>intval($data[$p['id']]),
                        ));
                    }
                    break;
                case PropsTable::ELEMENT_CHECKBOX:
                    if(isset($data[$p['id']])){
                        foreach($data[$p['id']] as $v){
                            \F::table($this->models['int'])->insert(array(
                                $this->foreign_key=>$refer,
                                'prop_id'=>$p['id'],
                                'content'=>intval($v),
                            ));
                        }
                    }
                    break;
                case PropsTable::ELEMENT_TEXTAREA:
                    if(!empty($data[$p['id']])){
                        \F::table($this->models['text'])->insert(array(
                            $this->foreign_key=>$refer,
                            'prop_id'=>$p['id'],
                            'content'=>$data[$p['id']],
                        ));
                    }
                    break;
                case PropsTable::ELEMENT_NUMBER:
                    if(!empty($data[$p['id']])){
                        \F::table($this->models['int'])->insert(array(
                            $this->foreign_key=>$refer,
                            'prop_id'=>$p['id'],
                            'content'=>intval($data[$p['id']]),
                        ));
                    }
                    break;
                case PropsTable::ELEMENT_IMAGE:
                    if(!empty($data[$p['id']])){
                        \F::table($this->models['int'])->insert(array(
                            $this->foreign_key=>$refer,
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
                    $value = \F::table($this->models['varchar'])->fetchRow(array(
                        "{$this->foreign_key} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    if($value){
                        $p['value'] = $value['content'];
                    }else{
                        $p['value'] = '';
                    }
                    break;
                case PropsTable::ELEMENT_RADIO:
                    $value = $sql->from(array('pi'=>\F::table($this->models['int'])->getTableName()), '')
                        ->joinLeft(array('v'=>'prop_values'), 'pi.content = v.id', 'id')
                        ->where(array(
                            "pi.{$this->foreign_key} = ?"=>$refer,
                            'pi.prop_id = ?'=>$p['id'],
                        ))
                        ->fetchRow()
                    ;
                    $p['value'] = $value['id'];
                    break;
                case PropsTable::ELEMENT_SELECT:
                    $value = $sql->from(array('pi'=>\F::table($this->models['int'])->getTableName()), '')
                        ->joinLeft(array('v'=>'prop_values'), 'pi.content = v.id', 'id')
                        ->where(array(
                            "pi.{$this->foreign_key} = ?"=>$refer,
                            'pi.prop_id = ?'=>$p['id'],
                        ))
                        ->fetchRow()
                    ;
                    $p['value'] = $value['id'];
                    break;
                case PropsTable::ELEMENT_CHECKBOX:
                    $value = $sql->from(array('pi'=>\F::table($this->models['int'])->getTableName()), '')
                        ->joinLeft(array('v'=>'prop_values'), 'pi.content = v.id', 'id')
                        ->where(array(
                            "pi.{$this->foreign_key} = ?"=>$refer,
                            'pi.prop_id = ?'=>$p['id'],
                        ))
                        ->fetchAll()
                    ;
                    $p['value'] = implode(',', ArrayHelper::column($value, 'id'));
                    break;
                case PropsTable::ELEMENT_TEXTAREA:
                    $value = \F::table($this->models['text'])->fetchRow(array(
                        "{$this->foreign_key} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    if($value){
                        $p['value'] = $value['content'];
                    }else{
                        $p['value'] = '';
                    }
                    break;
                case PropsTable::ELEMENT_NUMBER:
                    $value = \F::table($this->models['int'])->fetchRow(array(
                        "{$this->foreign_key} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    if($value){
                        $p['value'] = $value['content'];
                    }else{
                        $p['value'] = '';
                    }
                    break;
                case PropsTable::ELEMENT_IMAGE:
                    $value = \F::table($this->models['int'])->fetchRow(array(
                        "{$this->foreign_key} = ?"=>$refer,
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
                    $record = \F::table($this->models['varchar'])->fetchRow(array(
                        "{$this->foreign_key} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    if($record){
                        if($record['content'] != $data[$p['id']]){
                            \F::table($this->models['varchar'])->update(array(
                                'content'=>$data[$p['id']],
                            ), array(
                                "{$this->foreign_key} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                            ));
                        }
                    }else{
                        \F::table($this->models['varchar'])->insert(array(
                            $this->foreign_key=>$refer,
                            'prop_id'=>$p['id'],
                            'content'=>$data[$p['id']],
                        ));
                    }
                    break;
                case PropsTable::ELEMENT_RADIO:
                    $record = \F::table($this->models['int'])->fetchRow(array(
                        "{$this->foreign_key} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    if(empty($data[$p['id']])){
                        //若无提交值，且原先有值，则删除以前的值
                        if($record){
                            \F::table($this->models['int'])->delete(array(
                                "{$this->foreign_key} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                            ));
                        }
                    }else{
                        //如果存在，且值有变化，则更新；不存在，则插入
                        if($record){
                            if($record['content'] != $data[$p['id']]){
                                \F::table($this->models['int'])->update(array(
                                    'content'=>intval($data[$p['id']]),
                                ), array(
                                    "{$this->foreign_key} = ?"=>$refer,
                                    'prop_id = ?'=>$p['id'],
                                ));
                            }
                        }else{
                            \F::table($this->models['int'])->insert(array(
                                $this->foreign_key=>$refer,
                                'prop_id'=>$p['id'],
                                'content'=>intval($data[$p['id']]),
                            ));
                        }
                    }
                    break;
                case PropsTable::ELEMENT_SELECT:
                    $record = \F::table($this->models['int'])->fetchRow(array(
                        "{$this->foreign_key} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    if(empty($data[$p['id']])){
                        //若无提交值，且原先有值，则删除以前的值
                        if($record){
                            \F::table($this->models['int'])->delete(array(
                                "{$this->foreign_key} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                            ));
                        }
                    }else{
                        //如果存在，且值有变化，则更新；不存在，则插入
                        if($record){
                            if($record['content'] != $data[$p['id']]){
                                \F::table($this->models['int'])->update(array(
                                    'content'=>intval($data[$p['id']]),
                                ), array(
                                    "{$this->foreign_key} = ?"=>$refer,
                                    'prop_id = ?'=>$p['id'],
                                ));
                            }
                        }else{
                            \F::table($this->models['int'])->insert(array(
                                $this->foreign_key=>$refer,
                                'prop_id'=>$p['id'],
                                'content'=>intval($data[$p['id']]),
                            ));
                        }
                    }
                    break;
                case PropsTable::ELEMENT_CHECKBOX:
                    //获取已存在的项
                    $old_options = \F::table($this->models['int'])->fetchCol('content', array(
                        "{$this->foreign_key} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ));
                    if(isset($data[$p['id']])){
                        //删除已经不存在的项
                        $delete_options = array_diff($old_options, $data[$p['id']]);
                        if($delete_options){
                            \F::table($this->models['int'])->delete(array(
                                "{$this->foreign_key} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                                'content IN (?)'=>$delete_options,
                            ));
                        }
                        
                        //插入新增项
                        $new_options = array_diff($data[$p['id']], $old_options);
                        if($new_options){
                            foreach($new_options as $p_value){
                                \F::table($this->models['int'])->insert(array(
                                    $this->foreign_key=>$refer,
                                    'prop_id'=>$p['id'],
                                    'content'=>intval($p_value),
                                ));
                            }
                        }
                    }else{
                        //若无提交值，且原先有值，则删除以前的值
                        if($old_options){
                            \F::table($this->models['int'])->delete(array(
                                "{$this->foreign_key} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                            ));
                        }
                    }
                    break;
                case PropsTable::ELEMENT_TEXTAREA:
                    $record = \F::table($this->models['text'])->fetchRow(array(
                        "{$this->foreign_key} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    //如果存在，且值有变化，则更新；不存在，则插入
                    if($record){
                        if($record['content'] != $data[$p['id']]){
                            \F::table($this->models['text'])->update(array(
                                'content'=>$data[$p['id']],
                            ), array(
                                "{$this->foreign_key} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                            ));
                        }
                    }else{
                        \F::table($this->models['text'])->insert(array(
                            $this->foreign_key=>$refer,
                            'prop_id'=>$p['id'],
                            'content'=>$data[$p['id']],
                        ));
                    }
                    break;
                case PropsTable::ELEMENT_NUMBER:
                    //如果存在，且值有变化，则更新；不存在，则插入
                    $record = \F::table($this->models['int'])->fetchRow(array(
                        "{$this->foreign_key} = ?"=>$refer,
                        'prop_id = ?'=>$p['id'],
                    ), 'content');
                    if($record){
                        if($record['content'] != $data[$p['id']]){
                            \F::table($this->models['int'])->update(array(
                                'content'=>$data[$p['id']],
                            ), array(
                                "{$this->foreign_key} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                            ));
                        }
                    }else{
                        \F::table($this->models['int'])->insert(array(
                            $this->foreign_key=>$refer,
                            'prop_id'=>$p['id'],
                            'content'=>intval($data[$p['id']]),
                        ));
                    }
                    break;
                case PropsTable::ELEMENT_IMAGE:
                    if(empty($data[$p['id']])){
                        //若没有传值过来或传了空值，且原先有记录，则删除记录
                        if(\F::table($this->models['int'])->fetchRow(array(
                            "{$this->foreign_key} = ?"=>$refer,
                            'prop_id = ?'=>$p['id'],
                        ), 'content')){
                            \F::table($this->models['int'])->delete(array(
                                "{$this->foreign_key} = ?"=>$refer,
                                'prop_id = ?'=>$p['id'],
                            ));
                        }
                    }else{
                        //如果存在，且值有变化，则更新；不存在，则插入
                        $record = \F::table($this->models['int'])->fetchRow(array(
                            "{$this->foreign_key} = ?"=>$refer,
                            'prop_id = ?'=>$p['id'],
                        ), 'content');
                        if($record){
                            if($record['content'] != $data[$p['id']]){
                                \F::table($this->models['int'])->update(array(
                                    'content'=>$data[$p['id']],
                                ), array(
                                    "{$this->foreign_key} = ?"=>$refer,
                                    'prop_id = ?'=>$p['id'],
                                ));
                            }
                        }else{
                            \F::table($this->models['int'])->insert(array(
                                $this->foreign_key=>$refer,
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
                $record = \F::table($this->models['int'])->fetchRow(array(
                    "{$this->foreign_key} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ));
                if($record){
                    if($record['content'] != $value){
                        \F::table($this->models['int'])->update(array(
                            'content'=>intval($value),
                        ), array(
                            "{$this->foreign_key} = ?"=>$refer,
                            'prop_id = ?'=>$prop['id'],
                        ));
                    }
                }else{
                    \F::table($this->models['int'])->insert(array(
                        $this->foreign_key=>$refer,
                        'prop_id'=>$prop['id'],
                        'content'=>intval($value),
                    ));
                }
                break;
            case PropsTable::ELEMENT_CHECKBOX:
                if(is_array($value)){//$value是数组，完整更新
                    //删除已经不存在的项
                    \F::table($this->models['int'])->delete(array(
                        "{$this->foreign_key} = ?"=>$refer,
                        'prop_id = ?'=>$prop['id'],
                        'content NOT IN ('.implode(',', \F::filter('intval', $value)).')',
                    ));
                    //获取已存在的项
                    $old_options = \F::table($this->models['int'])->fetchCol('content', array(
                        "{$this->foreign_key} = ?"=>$refer,
                        'prop_id = ?'=>$prop['id'],
                    ));
                    //插入新增项
                    foreach($value as $p_value){
                        if(!in_array($p_value, $old_options)){
                            \F::table($this->models['int'])->insert(array(
                                $this->foreign_key=>$refer,
                                'prop_id'=>$prop['id'],
                                'content'=>intval($p_value),
                            ));
                        }
                    }
                }else{//$value不是数组，仅更新一个属性值选项
                    if(\F::table($this->models['int'])->fetchRow(array(
                        "{$this->foreign_key} = ?"=>$refer,
                        'prop_id = ?'=>$prop['id'],
                    ))){
                        \F::table($this->models['int'])->update(array(
                            'content'=>intval($value),
                        ), array(
                            "{$this->foreign_key} = ?"=>$refer,
                            'prop_id = ?'=>$prop['id'],
                        ));
                    }else{
                        \F::table($this->models['int'])->insert(array(
                            $this->foreign_key=>$refer,
                            'prop_id'=>$prop['id'],
                            'content'=>intval($value),
                        ));
                    }
                }
                break;
            case PropsTable::ELEMENT_TEXT:
                //如果存在，且值有变化，则更新；不存在，则插入
                $record = \F::table($this->models['text'])->fetchRow(array(
                    "{$this->foreign_key} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ));
                if($record){
                    if($record['content'] != $value){
                        \F::table($this->models['varchar'])->update(array(
                            'content' => $value,
                        ), array(
                            "{$this->foreign_key} = ?" => $refer,
                            'prop_id = ?' => $prop['id'],
                        ));
                    }
                }else{
                    \F::table($this->models['varchar'])->insert(array(
                        $this->foreign_key=>$refer,
                        'prop_id'=>$prop['id'],
                        'content'=>$value,
                    ));
                }
                break;
            case PropsTable::ELEMENT_TEXTAREA:
                //如果存在，且值有变化，则更新；不存在，则插入
                $record = \F::table($this->models['text'])->fetchRow(array(
                    "{$this->foreign_key} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ));
                if($record){
                    if($record['content'] != $value){
                        \F::table($this->models['text'])->update(array(
                            'content' => $value,
                        ), array(
                            "{$this->foreign_key} = ?" => $refer,
                            'prop_id = ?' => $prop['id'],
                        ));
                    }
                }else{
                    \F::table($this->models['text'])->insert(array(
                        $this->foreign_key=>$refer,
                        'prop_id'=>$prop['id'],
                        'content'=>$value,
                    ));
                }
                break;
            case PropsTable::ELEMENT_NUMBER:
                //如果存在，且值有变化，则更新；不存在，则插入
                $record = \F::table($this->models['int'])->fetchRow(array(
                    "{$this->foreign_key} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ));
                if($record){
                    if($record['content'] != $value){
                        \F::table($this->models['int'])->update(array(
                            'content'=>$value,
                        ), array(
                            "{$this->foreign_key} = ?"=>$refer,
                            'prop_id = ?'=>$prop['id'],
                        ));
                    }
                }else{
                    \F::table($this->models['int'])->insert(array(
                        $this->foreign_key=>$refer,
                        'prop_id'=>$prop['id'],
                        'content'=>$value,
                    ));
                }
                break;
            case PropsTable::ELEMENT_IMAGE:
                //如果存在，且值有变化，则更新；不存在，则插入
                $record = \F::table($this->models['int'])->fetchRow(array(
                    "{$this->foreign_key} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ));
                if($record){
                    if($record['content'] != $value){
                        \F::table($this->models['int'])->update(array(
                            'content'=>$value,
                        ), array(
                            "{$this->foreign_key} = ?"=>$refer,
                            'prop_id = ?'=>$prop['id'],
                        ));
                    }
                }else{
                    \F::table($this->models['int'])->insert(array(
                        $this->foreign_key=>$refer,
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
                $value = \F::table($this->models['varchar'])->fetchRow(array(
                    "{$this->foreign_key} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ), 'content');
                if($value){
                    return $value['content'];
                }else{
                    return '';
                }
            case PropsTable::ELEMENT_RADIO:
                return $sql->from(array('pi'=>\F::table($this->models['int'])->getTableName()), '')
                    ->joinLeft(array('v'=>'prop_values'), 'pi.content = v.id', 'id,title')
                    ->where(array(
                        "pi.{$this->foreign_key} = ?"=>$refer,
                        'pi.prop_id = ?'=>$prop['id'],
                    ))
                    ->fetchRow()
                ;
            case PropsTable::ELEMENT_SELECT:
            case PropsTable::ELEMENT_CHECKBOX:
                return $sql->from(array('pi'=>\F::table($this->models['int'])->getTableName()), '')
                    ->joinLeft(array('v'=>'prop_values'), 'pi.content = v.id', 'id,title')
                    ->where(array(
                        "pi.{$this->foreign_key} = ?"=>$refer,
                        'pi.prop_id = ?'=>$prop['id'],
                    ))
                    ->fetchAll()
                ;
            case PropsTable::ELEMENT_TEXTAREA:
                $value = \F::table($this->models['text'])->fetchRow(array(
                    "{$this->foreign_key} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ), 'content');
                if($value){
                    return $value['content'];
                }else{
                    return '';
                }
            case PropsTable::ELEMENT_NUMBER:
                $value = \F::table($this->models['int'])->fetchRow(array(
                    "{$this->foreign_key} = ?"=>$refer,
                    'prop_id = ?'=>$prop['id'],
                ), 'content');
                if($value){
                    return $value['content'];
                }else{
                    return '';
                }
            case PropsTable::ELEMENT_IMAGE:
                $value = \F::table($this->models['int'])->fetchRow(array(
                    "{$this->foreign_key} = ?"=>$refer,
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
    
    /**
     * 根据属性别名，获取可选的属性值
     * @param $alias
     * @return array|bool
     */
    public function getPropOptionsByAlias($alias){
        $prop = PropsTable::model()->fetchRow(array(
            'alias = ?'=>$alias,
            'delete_time = 0',
        ), 'id');
        if($prop){
            return PropValuesTable::model()->fetchAll(array(
                'prop_id = '.$prop['id'],
                'delete_time = 0',
            ), 'id,title,default', 'sort');
        }else{
            return false;
        }
    }
    
    /**
     * 根据属性别名，获取属性ID
     * @param string $alias
     * @return int
     */
    public function getIdByAlias($alias){
        $prop = PropsTable::model()->fetchRow(array(
            'alias = ?'=>$alias,
        ), 'id');
        return $prop['id'];
    }
}