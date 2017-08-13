<?php
namespace guangong\services;

use fay\core\Loader;
use fay\core\Service;
use cms\services\user\UserService;
use guangong\models\tables\GuangongTasksTable;
use guangong\models\tables\GuangongUserTasksTable;

class TaskService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }

    /**
     * 完成一个任务
     * @param int $task_id 任务ID
     * @param null|int $user_id
     * @return bool
     * @throws \Exception
     */
    public function done($task_id, $user_id = null){
        $user_id = UserService::makeUserID($user_id);
        
        $task = GuangongTasksTable::model()->find($task_id, 'enabled');
        if(!$task['enabled']){
            throw new \Exception('任务未开启');
        }
        
        $user_task = GuangongUserTasksTable::model()->fetchRow(array(
            'user_id = ?'=>$user_id,
            'create_date = ?'=>date('Y-m-d', \F::app()->current_time),
            'task_id = ?'=>$task_id,
        ));
        if($user_task){
            //今天已经做过此任务了，不抛出异常，直接返回false
            return false;
        }
        
        //插入任务完成记录
        GuangongUserTasksTable::model()->insert(array(
            'task_id'=>$task_id,
            'user_id'=>$user_id,
            'create_date'=>date('Y-m-d', \F::app()->current_time),
            'create_time'=>\F::app()->current_time,
        ));
        
        if($this->afterDone($user_id)){
            return 1;
        }
        return true;
    }
    
    /**
     * 完成任务后调用，判断用户是否已经完成当日所有任务
     * @param null|int $user_id
     * @return bool
     */
    private function afterDone($user_id = null){
        $user_id = UserService::makeUserID($user_id);
        
        //获取所有开启的任务
        $task_ids = GuangongTasksTable::model()->fetchCol('id', array(
            'enabled = 1'
        ));
        
        //获取所有已完成任务
        $done_tasks = GuangongUserTasksTable::model()->fetchAll(array(
            'user_id = ?'=>$user_id,
            'create_date = ?'=>date('Y-m-d', \F::app()->current_time),
            'task_id IN (?)'=>$task_ids,
        ), 'id');
        
        //开启任务数等于完成任务数，说明已经完成所有当日任务
        if(count($task_ids) == count($done_tasks)){
            return AttendanceService::service()->attend($user_id);
        }else{
            return null;
        }
    }
}