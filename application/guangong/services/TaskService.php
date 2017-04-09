<?php
namespace guangong\services;

use fay\core\ErrorException;
use fay\core\Exception;
use fay\core\Service;
use fay\services\user\UserService;
use guangong\models\tables\GuangongTasksTable;
use guangong\models\tables\GuangongUserTasksTable;

class TaskService extends Service{
    /**
     * @param string $class_name
     * @return TaskService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    /**
     * 完成一个任务
     * @param int $task_id 任务ID
     * @param null|int $user_id
     * @return bool
     * @throws ErrorException
     * @throws Exception
     */
    public function done($task_id, $user_id = null){
        if(!$user_id){
            $user_id = \F::app()->current_user;
        }else if(!UserService::isUserIdExist($user_id)){
            throw new ErrorException('指定用户ID不存在', 'user-id-is-not-exist');
        }
        
        $task = GuangongTasksTable::model()->find($task_id, 'enabled');
        if(!$task['enabled']){
            throw new Exception('任务未开启');
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
        
        $this->afterDone($user_id);
        return true;
    }
    
    /**
     * 完成任务后调用，判断用户是否已经完成当日所有任务
     * @param null|int $user_id
     * @throws ErrorException
     */
    private function afterDone($user_id = null){
        if($user_id === null){
            $user_id = \F::app()->current_user;
        }else if(!UserService::isUserIdExist($user_id)){
            throw new ErrorException('指定用户ID不存在', 'user-id-is-not-exist');
        }
        
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
            AttendanceService::service()->attend($user_id);
        }
    }
}