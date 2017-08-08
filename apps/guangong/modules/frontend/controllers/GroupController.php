<?php
namespace guangong\modules\frontend\controllers;

use fay\helpers\ArrayHelper;
use cms\services\user\UserService;
use guangong\library\FrontController;
use guangong\models\forms\CreateGroupForm;
use guangong\models\tables\GuangongUserExtraTable;
use guangong\models\tables\GuangongUserGroupsTable;
use guangong\models\tables\GuangongUserGroupUsersTable;

/**
 * 义结金兰
 */
class GroupController extends FrontController{
    public function index(){
        $this->form()->setModel(CreateGroupForm::model());
        
        $this->view->user_extra = GuangongUserExtraTable::model()->find($this->current_user);
        return $this->view->render();
    }
    
    /**
     * 拜帖
     */
    public function step2(){
        //表单验证
        $this->form()->setRules(array(
            array(array('group_id'), 'required'),
            array(array('group_id'), 'int', array('min'=>1)),
            array(array('group_id'), 'exist', array(
                'table'=>'guangong_user_groups',
                'field'=>'id',
            )),
        ))->setFilters(array(
            'group_id'=>'intval',
        ))->setLabels(array(
            'group_id'=>'结义ID',
        ))->check();
        
        $group = GuangongUserGroupsTable::model()->find($this->form()->getData('group_id'));
        $this->view->assign(array(
            'group'=>$group,
        ))->render();
    }
    
    /**
     * 盟誓
     */
    public function step3(){
        //表单验证
        $this->form()->setRules(array(
            array(array('group_id'), 'required'),
            array(array('group_id'), 'int', array('min'=>1)),
            array(array('group_id'), 'exist', array(
                'table'=>'guangong_user_groups',
                'field'=>'id',
            )),
        ))->setFilters(array(
            'id'=>'intval',
        ))->setLabels(array(
            'id'=>'结义ID',
        ))->check();
        
        $group = GuangongUserGroupsTable::model()->find($this->form()->getData('group_id'));
        $this->view->assign(array(
            'group'=>$group,
        ))->render();
    }
    
    /**
     * 兰谱
     */
    public function step4(){
        $this->view->assign(array(
            'groups'=>GuangongUserGroupsTable::model()->fetchAll(array(
                'user_id = ' . $this->current_user,
            ))
        ))->render();
    }
    
    /**
     * 解密
     */
    public function step5(){
        //表单验证
        $this->form()->setRules(array(
            array(array('group_id'), 'required'),
            array(array('group_id'), 'int', array('min'=>1)),
            array(array('group_id'), 'exist', array(
                'table'=>'guangong_user_groups',
                'field'=>'id',
            )),
        ))->setFilters(array(
            'id'=>'intval',
        ))->setLabels(array(
            'id'=>'结义ID',
        ))->check();
        
        $group_id = $this->form()->getData('group_id');
        $group_users = GuangongUserGroupUsersTable::model()->fetchAll(array(
            'group_id = ?'=>$group_id,
            'user_id != ' . $this->current_user,
        ), 'user_id,accept');
        
        $users = UserService::service()->mget(ArrayHelper::column($group_users, 'user_id'), 'id,nickname,avatar');
        
        $format_users = array();
        foreach($group_users as $gu){
            //未接受邀请，将个人信息置空
            $user = $users[$gu['user_id']];
            if(!$gu['accept']){
                $user['user']['id'] = '0';
                $user['user']['avatar'] = array();
                $user['user']['nickname'] = '';
            }
            $format_users[] = $user;
        }
        
        $this->view->assign(array(
            'group'=>GuangongUserGroupsTable::model()->find($group_id),
            'users'=>$format_users,
        ))->render();
    }
}