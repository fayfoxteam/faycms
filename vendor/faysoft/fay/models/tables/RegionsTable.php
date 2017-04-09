<?php
namespace fay\models\tables;

use fay\core\db\Table;

class RegionsTable extends Table{
    protected $_name = 'regions';
    
    /**
     * @param string $class_name
     * @return RegionsTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('id', 'parent_id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('type'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('name'), 'string', array('max'=>120)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'parent_id'=>'Parent Id',
            'name'=>'Name',
            'type'=>'Type',
        );
    }

    public function filters(){
        return array(
            'parent_id'=>'intval',
            'name'=>'trim',
            'type'=>'intval',
        );
    }
}