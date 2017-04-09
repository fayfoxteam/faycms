<?php
namespace guangong\models\tables;

use fay\core\db\Table;

/**
 * 结盟
 * 
 * @property int $id Id
 * @property string $name 称谓
 * @property int $user_id 发起者
 * @property string $vow 誓言
 * @property int $count 结义人数
 * @property int $create_time 发起时间
 */
class GuangongUserGroupsTable extends Table{
    protected $_name = 'guangong_user_groups';
    
    /**
     * @param string $class_name
     * @return GuangongUserGroupsTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('count'), 'int', array('min'=>0, 'max'=>255)),
            array(array('name'), 'string', array('max'=>20)),
            array(array('vow'), 'string', array('max'=>255)),
            
            array(array('name', 'count'), 'required'),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'称谓',
            'user_id'=>'发起者',
            'vow'=>'誓言',
            'count'=>'结义人数',
            'create_time'=>'发起时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'user_id'=>'intval',
            'vow'=>'trim',
            'count'=>'intval',
        );
    }
}