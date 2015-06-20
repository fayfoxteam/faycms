<?php
namespace fay\models\tables;

use fay\core\db\Table;

class ParentBiaos extends Table{
    protected $_name = 'parent_biaos';

    /**
     * @return ParentBiaos
     */
    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function rules(){
        return array(
            array(array('id', 'p_id', 'created', 'updated'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('name'), 'string', array('max'=>128)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'p_id'=>'P Id',
            'name'=>'Name',
            'created'=>'Created',
            'updated'=>'Updated',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'p_id'=>'intval',
            'name'=>'trim',
            'created'=>'intval',
            'updated'=>'intval',
        );
    }
}