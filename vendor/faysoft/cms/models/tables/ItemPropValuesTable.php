<?php
namespace cms\models\tables;

use fay\core\db\Table;

class ItemPropValuesTable extends Table{
    protected $_name = 'item_prop_values';
    
    /**
     * @param string $class_name
     * @return ItemPropValuesTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('cat_id', 'prop_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
            array(array('title', 'title_alias'), 'string', array('max'=>255)),
            array(array('is_terminal', 'delete_time'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'cat_id'=>'Cat Id',
            'prop_id'=>'Prop Id',
            'title'=>'Title',
            'title_alias'=>'Title Alias',
            'is_terminal'=>'Is Terminal',
            'delete_time'=>'删除时间',
            'sort'=>'Sort',
        );
    }

    public function filters(){
        return array(
            'cat_id'=>'intval',
            'prop_id'=>'intval',
            'title'=>'trim',
            'title_alias'=>'trim',
            'is_terminal'=>'intval',
            'delete_time'=>'intval',
            'sort'=>'intval',
        );
    }
}