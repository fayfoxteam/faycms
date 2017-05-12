<?php
namespace cms\services\prop;

use cms\models\tables\PropsRefersTable;
use cms\models\tables\PropsTable;
use cms\models\tables\PropValuesTable;
use fay\core\ErrorException;
use fay\core\HttpException;
use fay\core\Service;
use fay\helpers\ArrayHelper;
use fay\helpers\NumberHelper;
use fay\helpers\StringHelper;

class PropService extends Service{
    public static $selectable_element = array(
        PropsTable::ELEMENT_RADIO,
        PropsTable::ELEMENT_SELECT,
        PropsTable::ELEMENT_CHECKBOX,
    );
    
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

        if(in_array($prop['element'], self::$selectable_element)){
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
     */
    public function update($prop_id, $prop, $values = array()){
        PropsTable::model()->update($prop, $prop_id, true);

        //删除原有但现在没了的属性值
        PropValuesTable::model()->update(array(
            'delete_time'=>\F::app()->current_time,
        ),array(
            'prop_id = ?'=>$prop_id,
            'id NOT IN (?)'=>array_keys($values),
        ));
        //设置属性值
        if(in_array($prop['element'], self::$selectable_element)){//手工录入属性没有属性值
            $i = 0;
            foreach($values as $k => $v){
                $i++;
                if(NumberHelper::isInt($k) && PropValuesTable::model()->find($k, 'id')){
                    //若键是数字，且对应id存在，则更新
                    PropValuesTable::model()->update(array(
                        'title'=>$v,
                        'sort'=>$i,
                    ), array(
                        'id = ?'=>$k,
                    ));
                }else{
                    //插入
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
     * @throws HttpException
     */
    public function delete($id){
        $prop = PropsTable::model()->find($id, 'delete_time');
        if(!$prop || $prop['delete_time']){
            throw new HttpException("指定属性ID[{$id}]不存在或已删除", 404, 'prop_id-is-not-exist');
        }
        
        PropsTable::model()->update(array(
            'delete_time'=>\F::app()->current_time,
        ), $id);
    }

    /**
     * 还原自定义属性
     * @param int $id
     * @throws HttpException
     */
    public function undelete($id){
        $prop = PropsTable::model()->find($id, 'delete_time');
        if(!$prop || !$prop['delete_time']){
            throw new HttpException("指定属性ID[{$id}]不存在或未删除", 404, 'prop_id-is-not-exist');
        }
        
        PropsTable::model()->update(array(
            'delete_time'=>0,
        ), $id);
    }

    /**
     * 获取一个属性，若其为可选属性，则同时获取所有可选项
     * @param int $id
     * @return array
     * @throws ErrorException
     */
    public function get($id){
        $prop = PropsTable::model()->fetchRow(array(
            'id = ?'=>$id,
            'delete_time = 0',
        ));

        if(!$prop){
            throw new ErrorException("指定属性ID[{$id}]不存在");
        };

        if(in_array($prop['element'], self::$selectable_element)){
            $prop['values'] = PropValuesTable::model()->fetchAll(array(
                'prop_id = ?'=>$prop['id'],
                'delete_time = 0',
            ), '*', 'sort');
        }else{
            $prop['values'] = array();
        }

        return $prop;
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
     * @throws ErrorException
     */
    public function getIdByAlias($alias){
        $prop = PropsTable::model()->fetchRow(array(
            'alias = ?'=>$alias,
        ), 'id');

        if(!$prop){
            throw new ErrorException("指定属性别名[{$alias}]不存在");
        };
        
        return $prop['id'];
    }

    /**
     * 根据引用（例如：文章分类ID，用户角色ID）获取多个属性
     * @param int|array $refer 引用ID或引用ID构成的一维数组（若$refer为空而$parent_refers非空，不会进行搜索，直接返回空数组）
     * @param int $usage 属性用途
     * @param array $parent_refers 非直接引用，仅搜索is_share为1的属性
     * @param bool $with_values 若为true，则附加属性可选值。默认为true
     * @return array
     */
    public function getByRefer($refer, $usage, array $parent_refers = array(), $with_values = true){
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
        
        return $this->mget($prop_ids, $usage, $with_values, true);
    }

    /**
     * 获取一个或多个别名对应的属性
     * @param array|string $values 属性别名或ID构成的一维数组或逗号分割字符串
     *  以第一项为判断依据
     *   - 若第一项是数字，视为id
     *   - 若第一项不是数字，视为别名
     * @param int $usage 用途
     * @param bool $with_values 若为true，则附加属性可选值。默认为true
     * @param bool $sort 是否根据$values顺序返回结果
     * @return array
     */
    public function mget($values, $usage, $with_values = true, $sort = false){
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
                'type = ?'=>$usage,
                'delete_time = 0',
            ), 'id,title,type,required,element,alias');
        }else{
            //如果只有一项，搜索条件直接用等于
            $props = PropsTable::model()->fetchAll(array(
                "{$field} = ?"=>$values[0],
                'type = ?'=>$usage,
                'delete_time = 0',
            ), 'id,title,type,required,element,alias');
        }
        
        if($with_values && $props){
            //附加属性可选值
            $this->assembleOptions($props);
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
    private function assembleOptions(&$props){
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
            if(in_array($p['element'], self::$selectable_element)){
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
}