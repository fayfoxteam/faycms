<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\ActionlogsTable;
use cms\models\tables\ActionsTable;
use cms\models\tables\CategoriesTable;
use cms\services\CategoryService;
use cms\services\FlashService;
use fay\common\ListView;
use fay\exceptions\NotFoundHttpException;
use fay\core\Response;
use fay\core\Sql;

class ActionController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'role';
    }
    
    public function index(){
        $this->layout->subtitle = '添加权限';
        FlashService::set('如果您不清楚它的是干嘛用的，请不要随意修改，后果可能很严重！', 'warning');
        
        $this->_setListview();

        $this->view->cats = CategoryService::service()->getTree('_system_action');
        $this->form()->setModel(ActionsTable::model())
            ->setRule(array('parent_router', 'ajax', array('url'=>array('cms/admin/action/is-router-exist'))))
            ->setLabels(array('parent_router'=>'父级路由'))
        ;
        return $this->view->render();
    }
    
    public function create(){
        if($this->input->post()){
            if($this->form()->setModel(ActionsTable::model())
                ->setRule(array(array('parent_router',), 'exist', array('table'=>'actions', 'field'=>'router')))
                ->setLabels(array('parent_router'=>'父级路由'))
                ->check()){
                if($this->input->post('parent_router')){
                    $parent_router = ActionsTable::model()->fetchRow(array(
                        'router = ?'=>$this->input->post('parent_router', 'trim'),
                    ), 'id');
                    if(!$parent_router){
                        return Response::notify(Response::NOTIFY_FAIL, '父级路由不存在');
                    }
                    $parent = $parent_router['id'];
                }else{
                    $parent = 0;
                }
                $data = $this->form()->getFilteredData();
                $data['parent'] = $parent;
                $result = ActionsTable::model()->insert($data);
                $this->actionlog(ActionlogsTable::TYPE_ACTION, '添加权限', $result);
                return Response::notify(Response::NOTIFY_SUCCESS, '权限添加成功');
            }
        }else{
            return Response::notify(Response::NOTIFY_FAIL, '不完整的请求');
        }
    }
    
    public function edit(){
        $this->layout->subtitle = '编辑权限';
        $gets = $this->input->get();
        unset($gets['id']);
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/action/index', $gets),
            'text'=>'添加权限',
        );
        $action_id = intval($this->input->get('id', 'intval'));
        $this->view->cats = CategoryService::service()->getNextLevel('_system_action');
        
        $this->form()->setModel(ActionsTable::model())
            ->setRule(array(array('parent_router',), 'exist', array('table'=>'actions', 'field'=>'router', 'ajax'=>array('cms/admin/action/is-router-exist'))))
            ->setLabels(array('parent_router'=>'父级路由'));
        
        if($this->input->post()){
            if($this->form()->check()){
                if($this->input->post('parent_router')){
                    $parent_router = ActionsTable::model()->fetchRow(array(
                        'router = ?'=>$this->input->post('parent_router'),
                    ), 'id');
                    if(!$parent_router){
                        die('父级路由不存在');
                    }
                    $parent = $parent_router['id'];
                }else{
                    $parent = 0;
                }
                $data = $this->form()->getFilteredData();
                $data['parent'] = $parent;
                isset($data['is_public']) || $data['is_public'] = 0;
                ActionsTable::model()->update($data, "id = {$action_id}");
                $this->actionlog(ActionlogsTable::TYPE_ACTION, '编辑管理员权限', $action_id);
                FlashService::set('权限编辑成功', 'success');
            }
        }

        $action = ActionsTable::model()->find($action_id);
        if(!$action){
            throw new NotFoundHttpException("指定权限ID[{$action_id}]不存在");
        }
        if($action['parent']){
            $parent_action = ActionsTable::model()->find($action['parent'], 'router');
            $action['parent_router'] = $parent_action['router'];
        }
        $this->form()->setData($action);
        
        $this->_setListview();
        return $this->view->render();
    }
    
    public function remove(){
        ActionsTable::model()->delete(array('id = ?'=>$this->input->get('id', 'intval')));
        $this->actionlog(ActionlogsTable::TYPE_ACTION, '删除权限', $this->input->get('id', 'intval'));
        
        return Response::notify(Response::NOTIFY_SUCCESS, '一个权限被删除', $this->view->url('cms/admin/action/index', $this->input->get()));
    }
    
    public function search(){
        $actions = ActionsTable::model()->fetchAll(array(
            'router LIKE ?'=>'%'.$this->input->get('key', false).'%'
        ), 'id,router AS title', 'title', 20);
        
        return Response::json($actions);
    }
    
    public function isRouterNotExist(){
        if(ActionsTable::model()->has(array(
            'router = ?'=>$this->input->request('router', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval', false),
        ))){
            return Response::json('', 0, '该路由已存在');
        }else{
            return Response::json('', 1, '路由不存在');
        }
    }
    
    public function isRouterExist(){
        if(ActionsTable::model()->has(array(
            'router = ?'=>$this->input->request('router', 'trim'),
        ))){
            return Response::json('', 1, '路由已存在');
        }else{
            return Response::json('', 0, '路由不存在');
        }
    }
    
    /**
     * 设置右侧列表
     */
    private function _setListview(){
        $sql = new Sql();
        $sql->from(array('a'=>'actions'))
            ->joinLeft(array('c'=>'categories'), 'a.cat_id = c.id', 'title AS cat_title')
            ->joinLeft(array('pa'=>'actions'), 'a.parent = pa.id', 'router AS parent_router,title AS parent_title')
            ->joinLeft(array('pc'=>'categories'), 'pa.cat_id = pc.id', 'title AS parent_cat_title')
            ->order('a.cat_id');
        if($this->input->get('cat_id')){
            $sql->where(array(
                'a.cat_id = ?'=>$this->input->get('cat_id', 'intval'),
            ));
        }
        
        if($this->input->get('search_router')){
            $keywords = '%' . $this->input->get('search_router', 'trim') . '%';
            $sql->orWhere(array(
                'a.router LIKE ?'=>$keywords,
                'a.title LIKE ?'=>$keywords,
                'c.title LIKE ?'=>$keywords,
            ));
        }
        $this->view->listview = new ListView($sql);
    }

    public function cat(){
        $this->layout->subtitle = '权限分类';
        FlashService::set('如果您不清楚它的是干嘛用的，请不要随意修改，后果可能很严重！', 'warning');
        
        $this->view->cats = CategoryService::service()->getTree('_system_action');
        $root_node = CategoryService::service()->get('_system_action', 'id');
        $this->view->root = $root_node['id'];

        \F::form('create')->setModel(CategoriesTable::model());
        \F::form('edit')->setModel(CategoriesTable::model());
        
        $this->layout->sublink = array(
            'uri'=>'#create-cat-dialog',
            'text'=>'添加权限分类',
            'html_options'=>array(
                'class'=>'create-cat-link',
                'data-title'=>'权限分类',
                'data-id'=>$root_node['id'],
            ),
        );
        
        return $this->view->render();
    }
}