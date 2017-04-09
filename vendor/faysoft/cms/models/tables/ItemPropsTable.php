<?php
namespace cms\models\tables;

use fay\core\db\Table;

class ItemPropsTable extends Table{
    protected $_name = 'item_props';
    
    /**
     * @param string $class_name
     * @return ItemPropsTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('parent_vid'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('id', 'cat_id', 'parent_pid'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('required', 'multi'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('type'), 'int', array('min'=>0, 'max'=>255)),
            array(array('title'), 'string', array('max'=>255)),
            array(array('is_input_prop', 'is_sale_prop', 'is_color_prop', 'is_enum_prop', 'delete_time'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'is_input_prop'=>'Is Input Prop',
            'type'=>'Type',
            'cat_id'=>'Cat Id',
            'required'=>'Required',
            'parent_pid'=>'Parent Pid',
            'parent_vid'=>'Parent Vid',
            'title'=>'Title',
            'is_sale_prop'=>'Is Sale Prop',
            'is_color_prop'=>'Is Color Prop',
            'is_enum_prop'=>'Is Enum Prop',
            'delete_time'=>'删除时间',
            'multi'=>'Multi',
        );
    }

    public function filters(){
        return array(
            'is_input_prop'=>'intval',
            'type'=>'intval',
            'cat_id'=>'intval',
            'required'=>'intval',
            'parent_pid'=>'intval',
            'parent_vid'=>'intval',
            'title'=>'trim',
            'is_sale_prop'=>'intval',
            'is_color_prop'=>'intval',
            'is_enum_prop'=>'intval',
            'delete_time'=>'intval',
            'multi'=>'intval',
        );
    }
}