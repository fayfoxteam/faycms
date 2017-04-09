<?php
namespace guangong\modules\frontend\controllers;

use fay\services\post\PostService;
use guangong\library\FrontController;
use guangong\models\tables\GuangongReadLogsTable;
use guangong\services\TaskService;

class PostController extends FrontController{
    public function item(){
        //表单验证
        $this->form()->setRules(array(
            array(array('id'), 'required'),
            array(array('id'), 'int', array('min'=>1)),
            array(array('id'), 'exist', array(
                'table'=>'posts',
                'field'=>'id',
            )),
        ))->setFilters(array(
            'id'=>'intval',
        ))->setLabels(array(
            'id'=>'文章ID',
        ))->check();
        
        $post_id = $this->form()->getData('id');
        if($this->current_user){
            //若是登录用户访问此页面，记录阅读
            if(!GuangongReadLogsTable::model()->fetchRow(array(
                'user_id = ' . $this->current_user,
                'post_id = ?'=>$post_id,
            ))){
                GuangongReadLogsTable::model()->insert(array(
                    'user_id'=>$this->current_user,
                    'post_id'=>$post_id,
                    'create_time'=>$this->current_time,
                    'create_date'=>date('Y-m-d'),
                ));
                
                TaskService::service()->done(3);//做任务，id写死算了
            }
        }
        
        $this->view->renderPartial(null, array(
            'post'=> PostService::service()->get($post_id),
            'title'=>'资料库',
        ));
    }
    
}