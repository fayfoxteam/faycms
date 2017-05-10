<?php
namespace cms\models\tables;

use fay\core\db\Table;

/**
 * 属性引用关系
 *
 * @property int $id Id
 * @property int $refer 关联ID
 * @property int $prop_id 属性ID
 * @property int $is_share 是否与关联引用共享属性
 * @property int $sort 排序值
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
            array(array('id', 'refer', 'prop_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
            array(array('is_share'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'refer'=>'关联ID',
            'prop_id'=>'属性ID',
            'is_share'=>'是否与关联引用共享属性',
            'sort'=>'排序值',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'refer'=>'intval',
            'prop_id'=>'intval',
            'is_share'=>'intval',
            'sort'=>'intval',
        );
    }
}