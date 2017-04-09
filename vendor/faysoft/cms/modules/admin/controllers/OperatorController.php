<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\UsersTable;
use fay\core\Sql;
use fay\models\tables\RolesTable;
use fay\models\tables\ActionlogsTable;
use fay\common\ListView;
use fay\services\user\UserPropService;
use fay\services\user\UserService;
use fay\core\Response;
use fay\helpers\HtmlHelper;
use fay\core\HttpException;
use fay\core\Loader;
use fay\models\tables\UserProfileTable;
use fay\services\user\UserRoleService;

class OperatorController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'user';
    }
    
    public function index(){
        //搜索条件验证，异常数据直接返回404
        $this->form('search')->setScene('final')->setRules(array(
            array('orderby', 'range', array(
                'range'=>array_merge(
                    UsersTable::model()->getFields(),
                    UserProfileTable::model()->getFields()
                ),
            )),
            array('order', 'range', array(
                'range'=>array('asc', 'desc'),
            )),
            array('keywords_field', 'range', array(
                'range'=>array_merge(
                    UsersTable::model()->getFields(),
                    UserProfileTable::model()->getFields()
                ),
            )),
        ))->check();
        
        $this->layout->subtitle = '所有管理员';
            
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/operator/create'),
            'text'=>'添加管理员',
        );
        
        //页面设置
        $this->settingForm('admin_operator_index', '_setting_index', array(
            'cols'=>array('roles', 'mobile', 'email', 'realname', 'reg_time'),
            'page_size'=>20,
        ));
        
        //查询所有管理员类型
        $this->view->roles = RolesTable::model()->fetchAll(array(
            'delete_time = 0',
            'admin = 1',
        ));
        
        $sql = new Sql();
        $sql->from(array('u'=>'users'), '*')
            ->joinLeft(array('up'=>'user_profile'), 'u.id = up.user_id', '*')
            ->where('u.admin = 1')
        ;
        
        if($this->input->get('keywords')){
            if($this->input->get('field') == 'id'){
                $sql->where(array(
                    'u.id = ?'=>$this->input->get('keywords', 'intval'),
                ));
            }else{
                $field = $this->input->get('field');
                if(in_array($field, UsersTable::model()->getFields())){
                    $sql->where(array(
                        "u.{$field} LIKE ?"=>'%'.$this->input->get('keywords').'%',
                    ));
                }else{
                    $sql->where(array(
                        "up.{$field} LIKE ?"=>'%'.$this->input->get('keywords').'%',
                    ));
                }
            }
        }
        
        if($this->input->get('role')){
            $sql->joinLeft(array('ur'=>'users_roles'), 'u.id = ur.user_id')
                ->where(array(
                    'ur.role_id = ?' => $this->input->get('role', 'intval'),
                ));
        }
        
        if($this->input->get('orderby')){
            $this->view->orderby = $this->input->get('orderby');
            $this->view->order = $this->input->get('order') == 'asc' ? 'ASC' : 'DESC';
            $sql->order("{$this->view->orderby} {$this->view->order}");
        }else{
            $sql->order('u.id DESC');
        }
        
        $this->view->listview = new ListView($sql);
        
        $this->view->render();
    }
    
    public function create(){
        $this->layout->subtitle = '添加管理员';
        
        $this->form()->setScene('create')
            ->setModel(UsersTable::model())
            ->setModel(UserProfileTable::model())
            ->setRules(array(
                array(array('username', 'password'), 'required'),
                array('roles', 'int'),
            ));
        if($this->input->post() && $this->form()->check()){
            $data = UsersTable::model()->fillData($this->input->post());
            isset($data['status']) || $data['status'] = UsersTable::STATUS_VERIFIED;
            
            $extra = array(
                'profile'=>array(
                    'trackid'=>'admin_create:'.\F::session()->get('user.id'),
                ),
                'roles'=>$this->input->post('roles', 'intval', array()),
                'props'=>$this->input->post('props', '', array()),
            );
            
            $user_id = UserService::service()->create($data, $extra, 1);
            
            $this->actionlog(ActionlogsTable::TYPE_USERS, '添加了一个管理员', $user_id);
            
            Response::notify('success', '管理员添加成功， '.HtmlHelper::link('继续添加', array('cms/admin/operator/create', array(
                'roles'=>$this->input->post('roles', 'intval', array()),
            ))), array('cms/admin/operator/edit', array(
                'id'=>$user_id,
            )));
        }
        $this->view->roles = RolesTable::model()->fetchAll(array(
            'admin = 1',
            'delete_time = 0',
        ), 'id,title');
        
        //有可能默认了某些角色
        $role_ids = $this->input->get('roles', 'intval');
        if($role_ids){
            $this->view->prop_set = UserPropService::service()->getByRefer($role_ids);
        }else{
            $this->view->prop_set = array();
        }
        
        $this->view->render();
    }
    
    public function edit(){
        $this->layout->subtitle = '编辑管理员信息';
        $user_id = $this->input->request('id', 'intval');
        
        if(UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN, $user_id) && !UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN)){
            throw new HttpException('您无权编辑超级管理员账户', '403');
        }
        
        $this->form()->setScene('edit')
            ->setModel(UsersTable::model());
        if($this->input->post() && $this->form()->check()){
            $data = UsersTable::model()->fillData($this->input->post());
            
            $extra = array(
                'roles'=>$this->input->post('roles', 'intval', array()),
                'props'=>$this->input->post('props', '', array()),
            );
            
            UserService::service()->update($user_id, $data, $extra);
            
            $this->actionlog(ActionlogsTable::TYPE_PROFILE, '编辑了管理员信息', $user_id);
            Response::notify('success', '修改成功', false);
            
            //置空密码字段
            $this->form()->setData(array('password'=>''), true);
        }
        
        $user = UserService::service()->get($user_id, 'user.*,profile.*');
        $user_role_ids = UserRoleService::service()->getIds($user_id);
        $this->view->user = $user;
        $this->form()->setData($user['user'])
            ->setData(array('roles'=>$user_role_ids));
        
        $this->view->roles = RolesTable::model()->fetchAll(array(
            'admin = 1',
            'delete_time = 0',
        ), 'id,title');    
        
        $this->view->prop_set = UserPropService::service()->getPropertySet($user_id);
        $this->view->render();
    }
    
    public function item(){
        if($id = $this->input->get('id', 'intval')){
            $this->view->user = UserService::service()->get($id, 'user.*,props.*,roles.title,profile.*');
        }else{
            throw new HttpException('参数不完整', 500);
        }
        
        $this->layout->subtitle = "管理员 - {$this->view->user['user']['username']}";
        
        Loader::vendor('IpLocation/IpLocation.class');
        $this->view->iplocation = new \IpLocation();
        
        if($this->checkPermission('cms/admin/operator/edit')){
            $this->layout->sublink = array(
                'uri'=>array('cms/admin/operator/edit', array('id'=>$id)),
                'text'=>'编辑管理员',
            );
        }
        
        $this->view->render();
    }
}