<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use guangong\models\tables\GuangongUserGroupUsersTable;
use guangong\services\GroupService;

class WordController extends ApiController{
    /**
     * 设置我想对兄弟们说
     */
    public function set(){
        $this->checkLogin();
        
        //表单验证
        $this->form()->setRules(array(
            array(array('group_id', 'words', 'secrecy_period'), 'required'),
            array(array('words'), 'string', array('max'=>200)),
            array(array('secrecy_period'), 'range', array('range'=>array(365, 365 * 2, 365 * 3))),
            array(array('group_id'), 'int', array('min'=>1)),
            array(array('group_id'), 'exist', array(
                'table'=>'guangong_user_groups',
                'field'=>'id',
            )),
        ))->setFilters(array(
            'group_id'=>'intval',
            'words'=>'trim',
            'secrecy_period'=>'intval',
        ))->setLabels(array(
            'group_id'=>'结义ID',
            'words'=>'我想对兄弟们说',
            'secrecy_period'=>'保密期',
        ))->check();
        
        $group_user = GuangongUserGroupUsersTable::model()->fetchRow(array(
            'group_id = ?'=>$this->form()->getData('group_id'),
            'user_id = '.$this->current_user,
        ));
        if(!$group_user){
            Response::notify('error', '您不是该结义成员');
        }
        
        GuangongUserGroupUsersTable::model()->update(array(
            'words'=>$this->form()->getData('words'),
            'public_time'=>time() + $this->form()->getData('secrecy_period') * 86400,
        ), $group_user['id']);
        
        Response::notify('success', '设置成功');
    }
    
    public function get(){
        //表单验证
        $this->form()->setRules(array(
            array(array('group_id', 'user_id'), 'required'),
            array(array('group_id', 'user_id'), 'int', array('min'=>1)),
            array(array('group_id'), 'exist', array(
                'table'=>'guangong_user_groups',
                'field'=>'id',
            )),
        ))->setFilters(array(
            'id'=>'intval',
        ))->setLabels(array(
            'id'=>'结义ID',
        ))->check();
        
        $word = GuangongUserGroupUsersTable::model()->fetchRow(array(
            'group_id = ?'=>$this->form()->getData('group_id'),
            'user_id = ?'=>$this->form()->getData('user_id'),
        ));
        
        if(!$word['accept']){
            Response::notify('error', '该用户未接受结义邀请，无法查看密语');
        }
        
        if($word['public_time'] > $this->current_time){
            Response::notify('error', '离解密还有'.intval(($word['public_time'] - $this->current_time) / 86400).'天，请耐心等待。');
        }
        
        if(!GroupService::inGroup($word['group_id'], $this->current_user)){
            Response::notify('error', '您不属于指定结义成员，无法查看密语');
        }
        
        Response::json($word['words']);
    }
}