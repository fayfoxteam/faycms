<?php
namespace fayoauth\modules\admin\controllers;

use cms\library\AdminController;
use cms\services\FlashService;
use fay\common\ListView;
use fay\exceptions\NotFoundHttpException;
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
        return $this->view->render();
    }
    
    /**
     * 创建
     */
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
    
            Response::notify(Response::NOTIFY_SUCCESS, 'App添加成功');
        }else{
            Response::notify(Response::NOTIFY_FAIL, '无数据提交', array(
                'fayoauth/admin/app/index'
            ));
        }
    }
    
    /**
     * 编辑
     * @throws NotFoundHttpException
     */
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
            throw new NotFoundHttpException("指定AppID[{$id}]不存在");
        }
        $this->form()->setData($app);
        return $this->view->render();
    }
    
    /**
     * 删除
     */
    public function delete(){
        OauthAppService::service()->delete($this->input->get('id'));
    
        Response::notify(Response::NOTIFY_SUCCESS, '一个app被删除', $this->view->url('fayoauth/admin/app/index', $this->input->get()));
    }
    
    /**
     * 还原
     */
    public function undelete(){
        OauthAppService::service()->delete($this->input->get('id'));
    
        Response::notify(Response::NOTIFY_SUCCESS, '一个app被删除', $this->view->url('fayoauth/admin/app/index', $this->input->get()));
    }
    
    public function isAliasNotExist(){
        if(OauthAppsTable::model()->fetchRow(array(
            'alias = ?'=>$this->input->request('alias', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval', false),
        ))){
            return Response::json('', 0, '该路由已存在');
        }else{
            return Response::json('', 1, '路由不存在');
        }
    }
    
    public function isAliasExist(){
        if(OauthAppsTable::model()->fetchRow(array(
            'alias = ?'=>$this->input->request('alias', 'trim'),
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
        $sql->from(array('a'=>'oauth_apps'))
            ->where('delete_time = 0')
            ->order('a.id DESC');
        
        $this->view->listview = new ListView($sql, array(
            'empty_text'=>'<tr><td colspan="4" align="center">无相关记录！</td></tr>'
        ));
    }
}