<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\common\ListView;
use fay\core\Response;
use fay\core\Sql;
use fay\helpers\ArrayHelper;
use fay\models\tables\UsersTable;
use fay\services\user\UserService;
use guangong\models\forms\CreateGroupForm;
use guangong\models\tables\GuangongUserGroupsTable;
use guangong\models\tables\GuangongUserGroupUsersTable;

class GroupController extends ApiController{
    /**
     * 发起结义
     */
    public function create(){
        $this->checkLogin();
        
        $this->form()->setModel(CreateGroupForm::model())
            ->check();
        
        $data = $this->form()->getAllData();
        $data['create_time'] = $this->current_time;
        $data['user_id'] = $this->current_user;
        
        $group_id = GuangongUserGroupsTable::model()->insert($data, true);
        
        Response::notify('success', array(
            'message'=>'成功发起结义',
            'data'=>array(
                'group'=>GuangongUserGroupsTable::model()->find($group_id, 'id,name')
            )
        ));
    }
    
    /**
     * 添加结义成员
     * @parameter int $group_id 介意ID
     * @parameter array $mobiles 成员手机号
     */
    public function addUser(){
        $this->checkLogin();
        
        $group_id = $this->input->post('group_id');
        $mobiles = $this->input->post('mobiles', 'trim');
        //去重
        $mobiles = array_unique($mobiles);
        if(!$group_id){
            Response::notify('error', array(
                'message'=>'结义ID不能为空',
                'code'=>'missing-parameter:group_id',
            ));
        }
        
        $group = GuangongUserGroupsTable::model()->find($group_id);
        if($group['user_id'] != $this->current_user){
            Response::notify('error', array(
                'message'=>'您无权操作指定结义',
                'code'=>'permission-denied',
            ));
        }
        
        if(count($mobiles) != $group['count'] - 1){
            Response::notify('error', array(
                'message'=>'成员数不匹配',
                'code'=>'mobile-count-not-match',
            ));
        }
        
        $user_ids = array($this->current_user);//成员必然包含自己
        foreach($mobiles as $m){
            if(!$m){
                Response::notify('error', array(
                    'message'=>'识别号不能为空',
                    'code'=>'missing-parameter:mobile',
                ));
            }
            $user = UsersTable::model()->fetchRow(array(
                'mobile = ?'=>$m
            ), 'id');
            if(!$user){
                Response::notify('error', array(
                    'message'=>'指定手机未注册此应用',
                    'code'=>'mobile-not-found',
                ));
            }
            if($user['id'] == $this->current_user){
                Response::notify('error', array(
                    'message'=>'结义时不能邀请自己',
                ));
            }
            
            $user_ids[] = $user['id'];
        }
        
        foreach($user_ids as $ui){
            GuangongUserGroupUsersTable::model()->insert(array(
                'group_id'=>$group_id,
                'user_id'=>$ui,
                'accept'=>0,
            ));
        }
        
        Response::notify('success', '成员添加成功');
    }
    
    /**
     * 接受结义邀请
     * @parameter int $id 结义成员ID
     */
    public function accept(){
        $this->checkLogin();
        
        //表单验证
        $this->form()->setRules(array(
            array(array('id'), 'required'),
            array(array('id'), 'int', array('min'=>1)),
            array(array('id'), 'exist', array(
                'table'=>'guangong_user_group_users',
                'field'=>'id',
            )),
        ))->setFilters(array(
            'id'=>'intval',
        ))->setLabels(array(
            'id'=>'结义成员ID',
        ))->check();
        
        $group_user = GuangongUserGroupUsersTable::model()->find($this->form()->getData('id'));
        if($group_user['user_id'] != $this->current_user){
            Response::notify('error', array(
                'message'=>'您无权操作指定结义',
                'code'=>'permission-denied',
            ));
        }
        
        GuangongUserGroupUsersTable::model()->update(array(
            'accept'=>1,
        ), $group_user['id']);
    }
    
    /**
     * 设置我想对兄弟们说
     */
    public function setWord(){
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
            'secrecy_period'=>$this->form()->getData('secrecy_period'),
        ), $group_user['id']);
        
        Response::notify('success', '设置成功');
    }
    
    /**
     * 我的结义列表
     */
    public function index(){
        //验证必须get方式发起请求
        $this->checkMethod('GET');
        
        //表单验证
        $this->form()->setRules(array(
            array(array('page', 'page_size'), 'int', array('min'=>1)),
        ))->setFilters(array(
            'page'=>'intval',
            'page_size'=>'intval',
        ))->setLabels(array(
            'page'=>'页码',
            'page_size'=>'分页大小',
        ))->check();
        
        $page = $this->form()->getData('page', 1);
        $page_size = $this->form()->getData('page_size', 20);
        
        $sql = new Sql();
        $sql->from(array('ug'=>'guangong_user_groups'), array('id', 'name', 'vow', 'create_time', 'user_id'))
            ->where('user_id = ?', $this->current_user)
            ->order('id DESC');
        
        $listview = new ListView($sql, array(
            'current_page'=>$page,
            'page_size'=>$page_size,
        ));
        
        $groups = $listview->getData();
        $user_map = UserService::service()->mget(
            ArrayHelper::column($groups, 'user_id'),
            'nickname,'
        );
        
        $format_groups = array();
        foreach($groups as $g){
            $format_groups[] = array(
                'group'=>$g,
                'user'=>$user_map[$g['user_id']],
            );
        }
        
        Response::json(array(
            'groups'=>$format_groups,
            'pager'=>$listview->getPager(),
        ));
    }
}