<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

class RegionsTable extends Table{
    protected $_name = 'regions';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
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