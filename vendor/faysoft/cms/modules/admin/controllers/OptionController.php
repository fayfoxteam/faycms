<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\ActionlogsTable;
use cms\models\tables\OptionsTable;
use cms\services\FlashService;
use cms\services\OptionService;
use fay\common\ListView;
use fay\exceptions\NotFoundHttpException;
use fay\core\Response;
use fay\core\Sql;

class OptionController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'site';
    }
    
    public function create(){
        if($this->input->post()){
            if($this->form()->setModel(OptionsTable::model())->check()){
                $data = $this->form()->getFilteredData();
                $data['create_time'] = $this->current_time;
                $data['update_time'] = $this->current_time;
                $option_id = OptionsTable::model()->insert($data);
                
                $this->actionlog(ActionlogsTable::TYPE_OPTION, '添加了一个系统参数', $option_id);
                
                Response::notify(Response::NOTIFY_SUCCESS, array(
                    'message'=>'站点参数添加成功',
                ));
            }else{
                Response::goback();
            }
        }else{
            Response::notify(Response::NOTIFY_FAIL, array(
                'message'=>'不完整的请求',
            ));
        }
    }
    
    public function edit(){
        $this->layout->subtitle = '编辑参数';
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/option/index', array('page'=>$this->input->get('page', 'intval', 1))),
            'text'=>'添加参数',
        );
        $option_id = $this->input->get('id', 'intval');
        $this->form()->setModel(OptionsTable::model());
        if($this->input->post() && $this->form()->check()){
            $data = $this->form()->getFilteredData();
            $data['update_time'] = $this->current_time;
            OptionsTable::model()->update($data, array('id = ?'=>$option_id));
            
            $this->actionlog(ActionlogsTable::TYPE_OPTION, '编辑了一个系统参数', $option_id);
            Response::notify(Response::NOTIFY_SUCCESS, '一个参数被编辑', false);
        }
        
        if($option = OptionsTable::model()->find($option_id)){
            $this->form()->setData($option);
            $this->view->option = $option;
            
            $this->_setListview();
            
            return $this->view->render();
        }else{
            throw new NotFoundHttpException('无效的ID');
        }
    }
    
    public function index(){
        FlashService::set('这是一个汇总表，如果您不清楚它的含义，请不要随意修改，后果可能很严重！', 'warning');
        $this->layout->subtitle = '添加参数';
        
        $this->_setListview();
        
        $this->form()->setModel(OptionsTable::model());
        
        return $this->view->render();
    }
    
    public function remove(){
        $option_id = $this->input->get('id', 'intval');
        
        if(!$option_id){
            Response::notify(Response::NOTIFY_FAIL, '未指定参数ID');
        }
        
        $option = OptionsTable::model()->find($option_id);
        if(!$option){
            Response::notify(Response::NOTIFY_FAIL, '指定参数ID不存在');
        }
        
        OptionsTable::model()->delete(array('id = ?'=>$option_id));
        
        $this->actionlog(ActionlogsTable::TYPE_OPTION, '移除了一个系统参数', $option['option_name']);
        
        Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'一个参数被永久删除',
        ), array('cms/admin/option/index', $this->input->get()));
    }
    
    public function isOptionNotExist(){
        if(OptionsTable::model()->has(array(
            'option_name = ?'=>$this->input->request('option_name', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval', 0),
        ))){
            return Response::json('', 0, '参数名已存在');
        }else{
            return Response::json();
        }
    }
    
    /**
     * 设置右侧列表
     */
    private function _setListview(){
        //搜索条件验证，异常数据直接返回404
        $this->form('search')->setScene('final')->setRules(array(
            array('orderby', 'range', array(
                'range'=>OptionsTable::model()->getFields(),
            )),
            array('order', 'range', array(
                'range'=>array('asc', 'desc'),
            )),
        ))->check();
        
        $sql = new Sql();
        $sql->from('options');

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
    
    public function set(){
        if($this->input->post()){
            $data = $this->input->post();
            unset($data['_submit']);//提交按钮不用保存
            OptionService::mset($data);
            Response::notify(Response::NOTIFY_SUCCESS, '保存成功');
        }
        Response::notify(Response::NOTIFY_FAIL, '无数据提交');
    }
}