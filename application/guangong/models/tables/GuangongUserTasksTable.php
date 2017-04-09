<?php
namespace guangong\models\tables;

use fay\core\db\Table;

/**
 * 用户任务记录表
 * 
 * @property int $id Id
 * @property int $user_id 用户ID
 * @property int $task_id 任务ID
 * @property string $create_date 日期
 * @property int $create_time 创建时间
 */
class GuangongUserTasksTable extends Table{
    protected $_name = 'guangong_user_tasks';
    
    /**
     * @param string $class_name
     * @return GuangongUserTasksTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('id', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('task_id'), 'int', array('min'=>0, 'max'=>65535)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'user_id'=>'用户ID',
            'task_id'=>'任务ID',
            'create_date'=>'日期',
            'create_time'=>'创建时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'user_id'=>'intval',
            'task_id'=>'intval',
            'create_date'=>'',
        );
    }
}