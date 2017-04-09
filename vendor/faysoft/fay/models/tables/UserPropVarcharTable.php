<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * User Prop Varchar model
 * 
 * @property int $user_id
 * @property int $prop_id
 * @property string $content
 */
class UserPropVarcharTable extends Table{
    protected $_name = 'user_prop_varchar';
    protected $_primary = array('user_id', 'prop_id');
    
    /**
     * @param string $class_name
     * @return UserPropVarcharTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('user_id', 'prop_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('content'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'user_id'=>'用户ID',
            'prop_id'=>'角色ID',
            'content'=>'角色值',
        );
    }

    public function filters(){
        return array(
            'user_id'=>'intval',
            'prop_id'=>'intval',
            'content'=>'trim',
        );
    }
}