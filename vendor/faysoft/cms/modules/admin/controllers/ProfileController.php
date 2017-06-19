<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\ActionlogsTable;
use cms\models\tables\RolesTable;
use cms\models\tables\UsersTable;
use cms\services\user\UserPropService;
use cms\services\user\UserRoleService;
use cms\services\user\UserService;
use fay\core\Response;

class ProfileController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'profile';
    }
    
    public function index(){
        $this->layout->subtitle = '编辑我的信息';
        $user_id = $this->current_user;
        $this->form()->setModel(UsersTable::model());
        if($this->input->post() && $this->form()->check()){
            //两次密码输入一致
            $data = UsersTable::model()->fillData($this->input->post());
            
            $extra = array(
                'props'=>array(
                    'data'=>$this->input->post('props', '', array()),
                    'labels'=>$this->input->post('labels', 'trim', array()),
                ),
            );
            
            UserService::service()->update($user_id, $data, $extra);
            
            $this->actionlog(ActionlogsTable::TYPE_PROFILE, '编辑了自己的信息', $user_id);
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
        
        $this->view->prop_set = UserPropService::service()->getPropSet($user_id);
        $this->view->render();
    }
}