<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\ActionlogsTable;
use cms\models\tables\RolesActionsTable;
use cms\models\tables\RolesCatsTable;
use cms\models\tables\RolesTable;
use cms\services\CategoryService;
use cms\services\OptionService;
use fay\common\ListView;
use fay\core\Response;
use fay\core\Sql;
use fay\helpers\HtmlHelper;

class RoleController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'role';
    }
    
    public function index(){
        $this->layout->subtitle = '角色';
        
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/role/create'),
            'text'=>'添加角色',
        );
        
        $sql = new Sql();
        $sql->from(array('r'=>'roles'))
            ->where(array(
                'delete_time = 0',
            ))
            ->order('id DESC');
        $listview = new ListView($sql);
        $listview->page_size = 15;
        $this->view->listview = $listview;
        return $this->view->render();
    }
    
    public function create(){
        $this->layout->subtitle = '添加角色';
        
        $this->form()->setModel(RolesTable::model());
        if($this->input->post() && $this->form()->check()){
            $role_id = RolesTable::model()->insert($this->form()->getFilteredData());
            
            //操作权限
            $actions = $this->input->post('actions', 'intval', array());
            foreach($actions as $a){
                RolesActionsTable::model()->insert(array(
                    'role_id'=>$role_id,
                    'action_id'=>$a,
                ));
            }
            
            //分类权限
            $role_cats = $this->input->post('role_cats', 'intval', array());
            foreach($role_cats as $rc){
                RolesCatsTable::model()->insert(array(
                    'role_id'=>$role_id,
                    'cat_id'=>$rc,
                ));
            }
            
            $this->actionlog(ActionlogsTable::TYPE_ROLE, '添加了一个角色', $role_id);
            Response::notify(Response::NOTIFY_SUCCESS, '角色添加成功', array('cms/admin/role/edit', array(
                'id'=>$role_id,
            )));
        }
        $sql = new Sql();
        $actions = $sql->from(array('a'=>'actions'))
            ->joinLeft(array('c'=>'categories'), 'a.cat_id = c.id', 'title AS cat_title')
            ->fetchAll();
        
        $actions_group = array();
        //若未开启文章审核，过滤掉文章审核权限
        $post_review = OptionService::get('system:post_review');
        foreach($actions as $a){
            if(($a['router'] == 'cms/admin/post/review' || $a['router'] == 'cms/admin/post/publish')
                && !$post_review){
                continue;
            }
            
            $actions_group[$a['cat_title']][] = $a;
        }
        $this->view->actions = $actions_group;
        $this->view->cats = CategoryService::service()->getTree('_system_post');
        
        return $this->view->render();
    }
    
    public function edit(){
        $this->layout->subtitle = '编辑角色';
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/role/create'),
            'text'=>'添加角色',
        );
        $role_id = $this->input->get('id', 'intval');
        
        $this->form()->setModel(RolesTable::model());
        if($this->input->post() && $this->form()->check()){
            RolesTable::model()->update($this->form()->getFilteredData(), $role_id, true);
            
            //操作权限
            $actions = $this->input->post('actions', 'intval', array());
            if($actions){
                RolesActionsTable::model()->delete(array(
                    'role_id = ?'=>$role_id,
                    'action_id NOT IN (?)'=>$actions,
                ));
            }else{
                RolesActionsTable::model()->delete(array(
                    'role_id = ?'=>$role_id,
                ));
            }
            $old_actions = RolesActionsTable::model()->fetchCol('action_id', array(
                'role_id = ?'=>$role_id,
            ));
            
            foreach($actions as $a){
                if(!in_array($a, $old_actions)){
                    RolesActionsTable::model()->insert(array(
                        'role_id'=>$role_id,
                        'action_id'=>$a,
                    ));
                }
            }
            
            //分类权限
            $role_cats = $this->input->post('role_cats', 'intval', array(0));
            RolesCatsTable::model()->delete(array(
                'role_id = ?'=>$role_id,
                'cat_id NOT IN (?)'=>$role_cats,
            ));
            $old_role_cats = RolesCatsTable::model()->fetchCol('cat_id', array(
                'role_id = ?'=>$role_id,
            ));
            
            foreach($role_cats as $rc){
                if(!in_array($rc, $old_role_cats)){
                    RolesCatsTable::model()->insert(array(
                        'role_id'=>$role_id,
                        'cat_id'=>$rc,
                    ));
                }
            }
            
            //删除用户权限缓存
            \F::cache()->flush('user.actions');
            
            $this->actionlog(ActionlogsTable::TYPE_ROLE, '编辑了一个角色', $role_id);
            Response::notify(Response::NOTIFY_SUCCESS, '一个角色被编辑');
        }
        $role = RolesTable::model()->find($role_id);
        $this->form()->setData($role);
        
        $this->form()->setData(array(
            'actions'=>RolesActionsTable::model()->fetchCol('action_id', array('role_id = ?'=>$role_id)),
        ));
        
        $sql = new Sql();
        $actions = $sql->from(array('a'=>'actions'))
            ->joinLeft(array('c'=>'categories'), 'a.cat_id = c.id', 'title AS cat_title')
            ->fetchAll();
        
        $actions_group = array();
        //若未开启文章审核，过滤掉文章审核权限
        $post_review = OptionService::get('system:post_review');
        foreach($actions as $a){
            if(($a['router'] == 'cms/admin/post/review' || $a['router'] == 'cms/admin/post/publish')
                && !$post_review){
                continue;
            }
            
            $actions_group[$a['cat_title']][] = $a;
        }
        $this->view->actions = $actions_group;
        $this->form()->setData(array(
            'role_cats'=>RolesCatsTable::model()->fetchCol('cat_id', array(
                'role_id = ?'=>$role_id,
            )),
        ));
        $this->view->cats = CategoryService::service()->getTree('_system_post');
        
        return $this->view->render();
    }
    
    public function delete(){
        $role_id = $this->input->get('id', 'intval');
        RolesTable::model()->update(array(
            'delete_time'=>\F::app()->current_time,
        ), $role_id);
        $this->actionlog(ActionlogsTable::TYPE_ROLE, '删除了一个角色', $role_id);

        Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'一个角色被删除 - '.HtmlHelper::link('撤销', array('cms/admin/role/undelete', array(
                'id'=>$role_id,
            ))),
            'id'=>$role_id,
        ));
    }
    
    public function undelete(){
        $role_id = $this->input->get('id', 'intval');
        RolesTable::model()->update(array(
            'delete_time'=>0,
        ), $role_id);
        $this->actionlog(ActionlogsTable::TYPE_ROLE, '还原了一个角色', $role_id);

        Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'一个角色被还原',
            'id'=>$role_id,
        ));
    }
    
    public function isTitleNotExist(){
        if(RolesTable::model()->has(array(
            'title = ?'=>$value = $this->input->request('title', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval', 0),
        ))){
            return Response::json('', 0, '角色已存在');
        }else{
            return Response::json();
        }
    }
}