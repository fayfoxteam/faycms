<?php
namespace apidoc\modules\admin\controllers;

use apidoc\models\tables\ApidocErrorCodesTable;
use cms\library\AdminController;
use fay\common\ListView;
use fay\core\HttpException;
use fay\core\Response;
use fay\core\Sql;

class ErrorCodeController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'apidoc-api';
        $this->layout->_help_panel = '_help';
    }
    
    public function index(){
        $this->layout->subtitle = '错误码列表';
        
        //表单验证
        $this->form()->setModel(ApidocErrorCodesTable::model());

        $this->_setListview();
        return $this->view->render();
    }
    
    public function create(){
        if(!$this->input->post()){
            throw new HttpException('无数据提交');
        }

        if($this->form()->setModel(ApidocErrorCodesTable::model())->check()){
            $data = $this->form()->getFilteredData();
            $data['create_time'] = $data['update_time'] = $this->current_time;
            $error_code_id = ApidocErrorCodesTable::model()->insert($data, true);

            Response::notify('success', array(
                'message'=>'错误码添加成功',
                'id'=>$error_code_id,
            ));
        }else{
            Response::goback();
        }
    }
    
    public function edit(){
        $gets = $this->input->get();
        unset($gets['id']);
        $this->layout->sublink = array(
            'uri'=>array('apidoc/admin/error-code/index', $gets),
            'text'=>'添加错误码',
        );

        $error_code_id = $this->input->get('id', 'intval');

        $this->form()->setModel(ApidocErrorCodesTable::model());
        if($this->input->post() && $this->form()->check()){
            $data = $this->form()->getFilteredData();
            $data['update_time'] = $this->current_time;

            ApidocErrorCodesTable::model()->update($data, $error_code_id, true);

            Response::notify('success', '错误码编辑成功', false);
        }

        $error_code = ApidocErrorCodesTable::model()->find($error_code_id);
        $this->layout->subtitle = '编辑错误码 - ' . $error_code['code'];

        if(!$error_code){
            throw new HttpException('所选自定义属性不存在');
        }
        $this->form()->setData($error_code);


        $this->_setListview();
        return $this->view->render();
    }
    
    /**
     * 判断API错误码是否可用
     * 可用返回状态为1，不可用返回0，http状态码均为200
     * @parameter string $error_code 错误码
     */
    public function isErrorCodeNotExist(){
        //表单验证
        $this->form()->setRules(array(
            array('code', 'required'),
        ))->setFilters(array(
            'code'=>'trim',
        ))->setLabels(array(
            'code'=>'错误码',
        ))->check();
        
        if(ApidocErrorCodesTable::model()->has(array(
            'code = ?'=>$this->form()->getData('code'),
            'id != ?'=>$this->input->request('id', 'intval', false),
        ))){
            return Response::json('', 0, '错误码已存在');
        }else{
            return Response::json();
        }
    }

    /**
     * 删除属性
     * @parameter int $id
     */
    public function remove(){
        $id = $this->input->get('id', 'intval');
        ApidocErrorCodesTable::model()->delete($id);

        //不能直接回到上一页，因为可能处在编辑状态
        Response::notify(
            'success',
            '一个错误码被删除',
            array('apidoc/admin/error-code/index')
        );
    }

    public function search(){
        $keywords = '%'.$this->input->get('key', 'trim').'%';
        $error_codes = ApidocErrorCodesTable::model()->fetchAll(array(
            'or'=>array(
                'code LIKE ?'=>$keywords,
                'description LIKE ?'=>$keywords,
                
            )
        ), 'id,code,description,solution', 'code', 20);
        
        foreach($error_codes as &$error_code){
            $error_code['title'] = $error_code['code'];
            if($error_code['description']){
                $error_code['title'] .= "（{$error_code['description']}）";
            }
        }

        return Response::json($error_codes);
    }

    /**
     * 设置右侧列表
     */
    private function _setListview(){
        $sql = new Sql();
        $sql->from(array('ec'=>ApidocErrorCodesTable::model()->getTableName()))
            ->order('ec.id DESC');

        if($this->input->get('search_keywords')){
            $keywords = '%'.$this->input->get('search_keywords', 'trim').'%';
            $sql->orWhere(array(
                'ec.code LIKE ?'=>$keywords,
                'ec.description LIKE ?'=>$keywords,
            ));
        }

        $this->view->listview = new ListView($sql, array(
            'page_size'=>15,
            'empty_text'=>'<tr><td colspan="4" align="center">无相关记录！</td></tr>',
        ));
    }
}