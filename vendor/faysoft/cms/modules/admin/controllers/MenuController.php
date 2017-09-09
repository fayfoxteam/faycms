<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\ActionlogsTable;
use cms\models\tables\MenusTable;
use cms\models\tables\RolesTable;
use cms\services\MenuService;
use cms\services\user\UserRoleService;
use fay\core\Response;

class MenuController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'menu';
    }
    
    public function index(){
        $this->layout->subtitle = '导航栏';
        $this->view->menus = MenuService::service()->getTree('_user_menu', false, false);
        $this->view->root = MenuService::service()->get('_user_menu');
        if($this->checkPermission('cms/admin/menu/create')){
            $this->layout->sublink = array(
                'uri'=>'javascript:',
                'text'=>'添加菜单集',
                'html_options'=>array(
                    'class'=>'create-menu-link',
                    'data-title'=>'菜单集',
                    'data-id'=>MenusTable::ITEM_USER_MENU,
                    'data-src'=>'#create-menu-dialog',
                    'data-caption'=>'',
                ),
            );
        }
        return $this->view->render();
    }
    
    public function create(){
        $this->form()->setModel(MenusTable::model());
        if($this->input->post()){
            if($this->form()->check()){
                $data = $this->form()->getFilteredData();
                
                $parent = $this->input->post('parent', 'intval', 0);
                $sort = $this->input->post('sort', 'intval', 100);
                
                $menu_id = MenuService::service()->create($parent, $sort, $data);
                
                $this->actionlog(ActionlogsTable::TYPE_MENU, '添加菜单', $menu_id);
                
                $menu = MenusTable::model()->find($menu_id);
                Response::notify(Response::NOTIFY_SUCCESS, array(
                    'data'=>$menu,
                    'message'=>"菜单{$menu['title']}被添加",
                ));
            }else{
                Response::notify(Response::NOTIFY_FAIL, '参数异常');
            }
        }else{
            Response::notify(Response::NOTIFY_FAIL, '请提交数据');
        }
    }
    
    public function remove(){
        $id = $this->input->get('id', 'intval');
        if(MenuService::service()->remove($id)){
            $this->actionlog(ActionlogsTable::TYPE_MENU, '移除导航', $id);
                
            Response::notify(Response::NOTIFY_SUCCESS, array(
                'message'=>'一个菜单被删除',
            ));
        }else{
            Response::notify(Response::NOTIFY_FAIL, '菜单不存在或已被删除');
        }
    }
    
    public function removeAll(){
        $id = $this->input->get('id', 'intval');
        if(MenuService::service()->removeAll($id)){
            $this->actionlog(ActionlogsTable::TYPE_MENU, '移除导航及其所有子节点', $id);
        
            Response::notify(Response::NOTIFY_SUCCESS, array(
                'message'=>'一个菜单组被删除',
            ));
        }else{
            Response::notify(Response::NOTIFY_FAIL, '删除失败');
        }
    }
    
    public function edit(){
        if($this->input->post()){
            if($this->form()->setModel(MenusTable::model())->check()){
                $id = $this->input->post('id', 'intval');
                $data = $this->form()->getFilteredData();
                
                $parent = $this->input->post('parent', 'intval');
                $sort = $this->input->post('sort', 'intval');
                    
                MenuService::service()->update($id, $data, $sort, $parent);
                
                $this->actionlog(ActionlogsTable::TYPE_MENU, '编辑了菜单', $id);
                
                $node = MenusTable::model()->find($id);
                Response::notify(Response::NOTIFY_SUCCESS, array(
                    'message'=>"菜单{$node['title']}被编辑",
                    'data'=>$node,
                ));
            }else{
                Response::notify(Response::NOTIFY_FAIL, '参数异常');
            }
        }else{
            Response::notify(Response::NOTIFY_FAIL, '请提交数据');
        }
    }
    
    public function sort(){
        $id = $this->input->get('id', 'intval');
        MenuService::service()->sort($id, $this->input->get('sort', 'intval'));
        $this->actionlog(ActionlogsTable::TYPE_MENU, '改变了菜单排序', $id);
        
        $node = MenusTable::model()->find($id, 'sort,title');
        Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>"菜单{$node['title']}的排序值被修改",
            'data'=>array(
                'sort'=>$node['sort'],
            ),
        ));
    }
    
    /**
     * 获取一条记录
     */
    public function get(){
        $menu = MenusTable::model()->find($this->input->get('id', 'intval'));
        $children = MenusTable::model()->fetchCol('id', array(
            'left_value > '.$menu['left_value'],
            'right_value < '.$menu['right_value'],
        ));
        return Response::json(array(
            'menu'=>$menu,
            'children'=>$children,
        ));
    }
    
    /**
     * 设置启用状态
     */
    public function setEnabled(){
        MenuService::service()->update($this->input->get('id', 'intval'), array(
            'enabled'=>$this->input->get('enabled', 'intval', 0),
        ));
        
        $menu = MenusTable::model()->find($this->input->get('id', 'intval'), 'enabled');
        Response::notify(Response::NOTIFY_SUCCESS, array(
            'data'=>array(
                'enabled'=>$menu['enabled'],
            ),
        ));
    }
    
    public function admin(){
        $this->layout->subtitle = '后台导航栏';
        $this->view->menus = MenuService::service()->getTree('_admin_menu', false, false);
        $this->view->root = MenuService::service()->get('_admin_menu');
        if(UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN)){
            $this->layout->sublink = array(
                'uri'=>'javascript:',
                'text'=>'添加菜单集',
                'html_options'=>array(
                    'class'=>'create-menu-link',
                    'data-title'=>'后台菜单集',
                    'data-id'=>MenusTable::ITEM_ADMIN_MENU,
                    'data-src'=>'#create-menu-dialog',
                    'data-caption'=>'',
                ),
            );
        }
        return $this->view->render();
    }
}