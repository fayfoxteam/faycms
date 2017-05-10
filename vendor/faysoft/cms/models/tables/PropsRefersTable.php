<?php
namespace cms\models\tables;

use fay\core\db\Table;

/**
 * 属性引用关系
 * 
 * @property int $id Id
 * @property int $refer 关联ID
 * @property int $prop_id 属性ID
 * @property int $is_final 当引用存在父子关系时，子节点是否继承此属性
 */
class PropsRefersTable extends Table{
    protected $_name = 'props_refers';
    
    /**
     * @param string $class_name
     * @return PropsRefersTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('refer', 'prop_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('is_final'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'refer'=>'关联ID',
            'prop_id'=>'属性ID',
            'is_final'=>'当引用存在父子关系时，子节点是否继承此属性',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'refer'=>'intval',
            'prop_id'=>'intval',
            'is_final'=>'intval',
        );
    }
}