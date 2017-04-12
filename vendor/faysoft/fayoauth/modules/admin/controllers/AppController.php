<?php
namespace fayoauth\modules\admin\controllers;

use cms\library\AdminController;
use cms\services\FlashService;
use fay\common\ListView;
use fay\core\HttpException;
use fay\core\Response;
use fay\core\Sql;
use fayoauth\models\tables\OauthAppsTable;
use fayoauth\services\OauthAppService;

/**
 * 第三方信息管理
 */
class AppController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'oauth';
    }
    
    public function index(){
        $this->layout->subtitle = '添加APP';
    
        $this->_setListview();
    
        $this->form()->setModel(OauthAppsTable::model());
        $this->view->render();
    }
    
    public function create(){
        if($this->input->post() && 
            $this->form()->setModel(OauthAppsTable::model())
                ->check()
        ){
            OauthAppService::service()->create(
                $this->form()->getData('app_id'),
                $this->form()->getData('app_secret'),
                $this->form()->getFilteredData()
            );
    
            Response::notify('success', 'App添加成功');
        }
    }
    
    public function edit(){
        $this->layout->subtitle = '编辑APP';
        $id = $this->input->get('id', 'intval');
        
        $this->form()->setModel(OauthAppsTable::model());
        
        if($this->input->post() && $this->form()->check()){
            OauthAppService::service()->update($id, $this->form()->getFilteredData());
            
            FlashService::set('APP编辑成功', 'success');
        }
        
        $this->_setListview();
    
        $app = OauthAppsTable::model()->find($id);
        if(!$app){
            throw new HttpException('指定App ID不存在');
        }
        $this->form()->setData($app);
        $this->view->render();
    }
    
    public function delete(){
        OauthAppsTable::model()->update(array(
            'delete_time'=>$this->current_time,
        ), $this->input->get('id', 'intval'));
    
        Response::notify('success', '一个app被删除', $this->view->url('fayoauth/admin/app/index', $this->input->get()));
    }
    
    public function isAliasNotExist(){
        if(OauthAppsTable::model()->fetchRow(array(
            'alias = ?'=>$this->input->request('alias', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval', false),
        ))){
            Response::json('', 0, '该路由已存在');
        }else{
            Response::json('', 1, '路由不存在');
        }
    }
    
    public function isAliasExist(){
        if(OauthAppsTable::model()->fetchRow(array(
            'alias = ?'=>$this->input->request('alias', 'trim'),
        ))){
            Response::json('', 1, '路由已存在');
        }else{
            Response::json('', 0, '路由不存在');
        }
    }
    
    /**
     * 设置右侧列表
     */
    private function _setListview(){
        $sql = new Sql();
        $sql->from(array('a'=>'oauth_apps'))
            ->where('delete_time = 0')
            ->order('a.id DESC');
        
        $this->view->listview = new ListView($sql);
    }
}