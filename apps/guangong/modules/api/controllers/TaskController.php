<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use cms\services\user\UserService;
use fay\core\Response;
use guangong\models\tables\GuangongRanksTable;
use guangong\models\tables\GuangongUserExtraTable;
use guangong\services\AttendanceService;
use guangong\services\TaskService;

class TaskController extends ApiController{
    /**
     * 做任务（不做验证，只要用户提交过来，就当成功了）
     * @parameter int $task_id
     */
    public function doAction(){
        $this->checkLogin();
        //表单验证
        $this->form()->setRules(array(
            array(array('task_id'), 'required'),
            array(array('task_id'), 'int', array('min'=>1)),
            array(array('task_id'), 'exist', array(
                'table'=>'guangong_tasks',
                'field'=>'id',
            )),
        ))->setFilters(array(
            'id'=>'intval',
        ))->setLabels(array(
            'id'=>'任务ID',
        ))->check();
        
        $result = TaskService::service()->done($this->form()->getData('task_id'));
        
        $user = GuangongUserExtraTable::model()->find($this->current_user);
        $rank = GuangongRanksTable::model()->find($user['rank_id']);
        if($result === 1){
            Response::notify(Response::NOTIFY_SUCCESS, array(
                'message'=>'军衔提升',
                'data'=>array(
                    'rank'=>$rank,
                    'user'=>UserService::service()->get($this->current_user, 'id,nickname,realname'),
                    'attendances'=>AttendanceService::service()->getCount(),
                ),
            ));
        }else if($result){
            Response::notify(Response::NOTIFY_SUCCESS, array(
                'message'=>'任务完成',
                'data'=>array(
                    'rank'=>$rank,
                    'attendances'=>AttendanceService::service()->getCount(),
                ),
            ));
        }else{
            //任务失败可能是重复调用什么的，不重要，不返回错误描述
            Response::notify(Response::NOTIFY_FAIL, array(
                'message'=>'',
                'data'=>array(
                    'rank'=>$rank,
                    'attendances'=>AttendanceService::service()->getCount(),
                ),
            ));
        }
    }
}