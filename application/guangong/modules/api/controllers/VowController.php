<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\helpers\ArrayHelper;
use guangong\models\tables\GuangongUserGroupsTable;
use guangong\models\tables\GuangongVowsTable;

class VowController extends ApiController{
    /**
     * 列出系统预定义誓言
     */
    public function listAction(){
        $vows = GuangongVowsTable::model()->fetchAll(array(
            'enabled = 1',
        ), 'content', 'sort');
        
        Response::json(ArrayHelper::column($vows, 'content'));
    }
    
    /**
     * 设置结盟誓言
     */
    public function setVow(){
        $this->checkLogin();
        
        //表单验证
        $this->form()->setRules(array(
            array(array('group_id', 'vow'), 'required'),
            array(array('vow'), 'string', array('max'=>100)),
            array(array('group_id'), 'int', array('min'=>1)),
            array(array('group_id'), 'exist', array(
                'table'=>'guangong_user_groups',
                'field'=>'id',
            )),
        ))->setFilters(array(
            'group_id'=>'intval',
            'vow'=>'trim',
        ))->setLabels(array(
            'group_id'=>'结义ID',
            'vow'=>'誓言',
        ))->check();
        
        $group = GuangongUserGroupsTable::model()->find($this->form()->getData('group_id'), 'id,user_id');
        if($group['user_id'] != $this->current_user){
            Response::notify('error', array(
                'message'=>'您无权操作指定结义',
                'code'=>'permission-denied',
            ));
        }
        
        GuangongUserGroupsTable::model()->update(array(
            'vow'=>$this->form()->getData('vow')
        ), $group['id']);
        
        Response::notify('success', '设置成功');
    }
}