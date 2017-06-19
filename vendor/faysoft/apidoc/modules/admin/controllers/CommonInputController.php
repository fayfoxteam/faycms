<?php
namespace apidoc\modules\admin\controllers;

use apidoc\models\tables\ApidocCommonInputsTable;
use cms\library\AdminController;
use fay\core\Response;
use fay\helpers\ArrayHelper;

/**
 * 公共请求参数
 */
class CommonInputController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'api';
    }

    public function index(){
        $this->layout->subtitle = '公共请求参数管理';

        $this->form('create')->setModel(ApidocCommonInputsTable::model());
        $this->form('edit')->setModel(ApidocCommonInputsTable::model());

        $this->view->assign(array(
            'common_inputs'=>ApidocCommonInputsTable::model()->fetchAll(array(), '*', 'sort')
        ))->render();
    }

    /**
     * 创建一个公共请求参数
     */
    public function create(){
        if($this->input->post()){
            if($this->form()->setModel(ApidocCommonInputsTable::model())->check()){
                $data = $this->form()->getFilteredData();
                $data['create_time'] = $this->current_time;
                $data['update_time'] = $this->current_time;
                ApidocCommonInputsTable::model()->insert($data);

                Response::notify('success', array(
                    'message'=>'公共请求参数添加成功',
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

    /**
     * 编辑一个公共请求参数
     */
    public function edit(){
        $common_input_id = $this->input->request('id', 'intval');

        if(!$common_input_id){
            Response::notify('error', '未指定公共请求参数ID');
        }

        $common_input = ApidocCommonInputsTable::model()->find($common_input_id);
        if(!$common_input){
            Response::notify('error', "指定公共请求参数ID[{$common_input_id}]不存在");
        }

        $this->form()->setModel(ApidocCommonInputsTable::model());
        if($this->input->post()){
            if($this->form()->check()){
                $data = $this->form()->getFilteredData();
                if(ArrayHelper::equal($data, $common_input)){
                    Response::notify('success', '没有字段被修改');
                }
                $data['update_time'] = $this->current_time;
                ApidocCommonInputsTable::model()->update($data, $common_input_id);

                Response::notify('success', '一个公共请求参数被编辑');
            }else{
                Response::goback();
            }
        }else{
            Response::notify('error', array(
                'message'=>'不完整的请求',
            ));
        }
    }

    /**
     * 物理删除一个公共请求参数
     */
    public function remove(){
        $common_input_id = $this->input->get('id', 'intval');

        if(!$common_input_id){
            Response::notify('error', '未指定公共请求参数ID');
        }

        $common_input = ApidocCommonInputsTable::model()->find($common_input_id);
        if(!$common_input){
            Response::notify('error', "指定公共请求参数ID[{$common_input_id}]不存在");
        }

        ApidocCommonInputsTable::model()->delete($common_input_id);

        Response::notify('success', array(
            'message'=>'一个公共请求参数被永久删除',
        ));
    }

    /**
     * 获取一个公共请求参数
     */
    public function get(){
        $common_input_id = $this->input->get('id', 'intval');

        if(!$common_input_id){
            Response::notify('error', '未指定公共请求参数ID');
        }

        $common_input = ApidocCommonInputsTable::model()->find($common_input_id);
        if(!$common_input){
            Response::notify('error', "指定公共请求参数ID[{$common_input_id}]不存在");
        }

        Response::json(array(
            'common_input'=>$common_input
        ));
    }

    /**
     * 验证指定公共请求参数名称是否可用
     */
    public function isNameNotExist(){
        $name = $this->input->request('name', 'trim');
        if(!$name){
            Response::json('', 0, '参数名称不能为空');
        }

        if(ApidocCommonInputsTable::model()->fetchRow(array(
            'name = ?'=>$name,
            'id != ?'=>$this->input->request('id', 'intval', false),
        ))){
            Response::json('', 0, '参数名称已存在');
        }else{
            Response::json();
        }
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
            ApidocCommonInputsTable::model()->update(array(
                'sort'=>$i,
            ), $s);
        }

        Response::notify('success', '排序保存成功');
    }
}