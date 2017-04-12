<?php
namespace cms\models\tables;

use fay\core\db\Table;

class PropValuesTable extends Table{
    protected $_name = 'prop_values';
    
    /**
     * @param string $class_name
     * @return PropValuesTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('refer', 'prop_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('default'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
            array(array('title'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'refer'=>'Refer',
            'prop_id'=>'Prop Id',
            'title'=>'Title',
            'default'=>'Default',
            'delete_time'=>'删除时间',
            'sort'=>'Sort',
        );
    }

    public function filters(){
        return array(
            'refer'=>'intval',
            'prop_id'=>'intval',
            'title'=>'trim',
            'default'=>'intval',
            'sort'=>'intval',
        );
    }
}