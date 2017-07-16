<?php
namespace apidoc\modules\admin\controllers;

use apidoc\models\tables\ApidocAppsTable;
use cms\library\AdminController;
use fay\core\Response;
use fay\helpers\ArrayHelper;

class AppController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'apidoc-api';
    }
    
    public function create(){
        if($this->input->post()){
            if($this->form()->setModel(ApidocAppsTable::model())->check()){
                $data = $this->form()->getFilteredData();
                $data['create_time'] = $this->current_time;
                $data['update_time'] = $this->current_time;
                ApidocAppsTable::model()->insert($data);
                
                Response::notify('success', array(
                    'message'=>'站点应用添加成功',
                ));
            }else{
                Response::goback();
            }
        }else{
            Response::notify('error', array(
                'message'=>'不完整的请求',
            ));
        }
    }
    
    public function edit(){
        $app_id = $this->input->request('id', 'intval');

        if(!$app_id){
            Response::notify('error', '未指定APP ID');
        }

        $app = ApidocAppsTable::model()->find($app_id);
        if(!$app){
            Response::notify('error', "指定APP ID[{$app_id}]不存在");
        }

        $this->form()->setModel(ApidocAppsTable::model());
        if($this->input->post()){
            if($this->form()->check()){
                $data = $this->form()->getFilteredData();
                if(ArrayHelper::equal($data, $app)){
                    Response::notify('success', '没有字段被修改');
                }
                $data['update_time'] = $this->current_time;
                ApidocAppsTable::model()->update($data, $app_id);

                Response::notify('success', '一个APP被编辑');
            }else{
                Response::goback();
            }
        }else{
            Response::notify('error', array(
                'message'=>'不完整的请求',
            ));
        }
    }
    
    public function index(){
        $this->layout->subtitle = 'APP 管理';

        $this->form('create')->setModel(ApidocAppsTable::model());
        $this->form('edit')->setModel(ApidocAppsTable::model());

        $this->view->assign(array(
            'apps'=>ApidocAppsTable::model()->fetchAll(array(), '*', 'sort')
        ))->render();
    }

    /**
     * 删除应用（物理删除）
     */
    public function remove(){
        $option_id = $this->input->get('id', 'intval');
        
        if(!$option_id){
            Response::notify('error', '未指定应用ID');
        }
        
        $option = ApidocAppsTable::model()->find($option_id);
        if(!$option){
            Response::notify('error', '指定应用ID不存在');
        }
        
        ApidocAppsTable::model()->delete(array('id = ?'=>$option_id));
        
        Response::notify('success', array(
            'message'=>'一个应用被永久删除',
        ), array('apidoc/admin/app/index', $this->input->get()));
    }

    /**
     * 判断应用名是否存在
     */
    public function isNameNotExist(){
        if(ApidocAppsTable::model()->has(array(
            'name = ?'=>$this->input->request('name', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval', 0),
        ))){
            Response::json('', 0, '应用名已存在');
        }else{
            Response::json();
        }
    }

    /**
     * 获取一个APP
     */
    public function get(){
        $app_id = $this->input->get('id', 'intval');

        if(!$app_id){
            Response::notify('error', '未指定APP ID');
        }

        $app = ApidocAppsTable::model()->find($app_id);
        if(!$app){
            Response::notify('error', "指定APP ID[{$app_id}]不存在");
        }

        Response::json(array(
            'app'=>$app
        ));
    }

    /**
     * 保存排序信息
     */
    public function sort(){
        $this->form()->setRule(array(
            array('sort', 'int', array('min'=>1))
        ));
        $sort = $this->input->post('sort', 'intval');

        $i = 0;
        foreach($sort as $s){
            $i++;
            ApidocAppsTable::model()->update(array(
                'sort'=>$i,
            ), $s);
        }

        Response::notify('success', '排序保存成功');
    }
}