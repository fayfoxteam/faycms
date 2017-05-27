<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

class RolesActionsTable extends Table{
    protected $_name = 'roles_actions';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('role_id', 'action_id'), 'int', array('min'=>0, 'max'=>65535)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'role_id'=>'Role Id',
            'action_id'=>'Action Id',
        );
    }

    public function filters(){
        return array(
            'role_id'=>'intval',
            'action_id'=>'intval',
        );
    }
}