<?php
namespace apidoc\modules\admin\controllers;

use apidoc\models\tables\ApidocAppsTable;
use cms\library\AdminController;
use fay\common\ListView;
use fay\core\HttpException;
use fay\core\Response;
use fay\core\Sql;

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
        $this->layout->subtitle = '编辑应用';
        $this->layout->sublink = array(
            'uri'=>array('apidoc/admin/app/index', array('page'=>$this->input->get('page', 'intval', 1))),
            'text'=>'添加应用',
        );
        $option_id = $this->input->get('id', 'intval');
        $this->form()->setModel(ApidocAppsTable::model());
        if($this->input->post() && $this->form()->check()){
            $data = $this->form()->getFilteredData();
            $data['update_time'] = $this->current_time;
            ApidocAppsTable::model()->update($data, array('id = ?'=>$option_id));
            
            Response::notify('success', '一个应用被编辑', false);
        }
        
        if($option = ApidocAppsTable::model()->find($option_id)){
            $this->form()->setData($option);
            $this->view->option = $option;
            
            $this->_setListview();
            
            $this->view->render();
        }else{
            throw new HttpException('无效的ID');
        }
    }
    
    public function index(){
        $this->layout->subtitle = '添加应用';
        
        $this->_setListview();
        
        $this->form()->setModel(ApidocAppsTable::model());
        
        $this->view->render();
    }
    
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
    
    public function isOptionNotExist(){
        if(ApidocAppsTable::model()->fetchRow(array(
            'option_name = ?'=>$this->input->request('option_name', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval', 0),
        ))){
            Response::json('', 0, '应用名已存在');
        }else{
            Response::json();
        }
    }
    
    /**
     * 设置右侧列表
     */
    private function _setListview(){
        //搜索条件验证，异常数据直接返回404
        $this->form('search')->setScene('final')->setRules(array(
            array('orderby', 'range', array(
                'range'=>ApidocAppsTable::model()->getFields(),
            )),
            array('order', 'range', array(
                'range'=>array('asc', 'desc'),
            )),
        ))->check();
        
        $sql = new Sql();
        $sql->from(ApidocAppsTable::model()->getTableName());

        if($this->input->get('orderby')){
            $this->view->orderby = $this->input->get('orderby');
            $this->view->order = $this->input->get('order') == 'asc' ? 'ASC' : 'DESC';
            $sql->order("{$this->view->orderby} {$this->view->order}");
        }else{
            $sql->order('id DESC');
        }
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>15,
            'empty_text'=>'<tr><td colspan="3" align="center">无相关记录！</td></tr>',
        ));
    }
}