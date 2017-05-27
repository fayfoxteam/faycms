<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

class RolesCatsTable extends Table{
    protected $_name = 'roles_cats';
    protected $_primary = array('role_id', 'cat_id');
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('role_id', 'cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
        );
    }

    public function labels(){
        return array(
            'role_id'=>'Role Id',
            'cat_id'=>'Cat Id',
        );
    }

    public function filters(){
        return array(
            'role_id'=>'intval',
            'cat_id'=>'intval',
        );
    }
}