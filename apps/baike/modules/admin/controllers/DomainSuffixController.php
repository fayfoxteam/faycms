<?php
namespace baike\modules\admin\controllers;

use baike\models\tables\BaikeDomainSuffixesTable;
use cms\library\AdminController;
use fay\core\Response;
use fay\helpers\ArrayHelper;

class DomainSuffixController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'domain';
    }
    
    public function index(){
        $this->layout->subtitle = '域名后缀管理';

        $this->form('create')->setModel(BaikeDomainSuffixesTable::model());
        $this->form('edit')->setModel(BaikeDomainSuffixesTable::model());
        
        $this->view->assign(array(
            'domain_suffixes'=>BaikeDomainSuffixesTable::model()->fetchAll(array(), '*', 'sort')
        ))->render();
    }
    
    public function create(){
        if($this->input->post()){
            if($this->form()->setModel(BaikeDomainSuffixesTable::model())->check()){
                $data = $this->form()->getFilteredData();
                $data['create_time'] = $this->current_time;
                $data['update_time'] = $this->current_time;
                BaikeDomainSuffixesTable::model()->insert($data);

                Response::notify('success', array(
                    'message'=>'域名后缀添加成功',
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
        $domain_suffix_id = $this->input->request('id', 'intval');

        if(!$domain_suffix_id){
            Response::notify('error', '未指定域名后缀ID');
        }

        $domain_suffix = BaikeDomainSuffixesTable::model()->find($domain_suffix_id);
        if(!$domain_suffix){
            Response::notify('error', "指定域名后缀ID[{$domain_suffix_id}]不存在");
        }

        $this->form()->setModel(BaikeDomainSuffixesTable::model());
        if($this->input->post()){
            if($this->form()->check()){
                $data = $this->form()->getFilteredData();
                if(ArrayHelper::equal($data, $domain_suffix)){
                    Response::notify('success', '没有字段被修改');
                }
                $data['update_time'] = $this->current_time;
                BaikeDomainSuffixesTable::model()->update($data, $domain_suffix_id);
    
                Response::notify('success', '一个域名后缀被编辑');
            }else{
                Response::goback();
            }
        }else{
            Response::notify('error', array(
                'message'=>'不完整的请求',
            ));
        }
    }
    
    public function remove(){
        $domain_suffix_id = $this->input->get('id', 'intval');

        if(!$domain_suffix_id){
            Response::notify('error', '未指定域名后缀ID');
        }

        $domain_suffix = BaikeDomainSuffixesTable::model()->find($domain_suffix_id);
        if(!$domain_suffix){
            Response::notify('error', "指定域名后缀ID[{$domain_suffix_id}]不存在");
        }

        BaikeDomainSuffixesTable::model()->delete($domain_suffix_id);

        Response::notify('success', array(
            'message'=>'一个域名后缀被永久删除',
        ), array('cms/admin/option/index', $this->input->get()));
    }
    
    public function get(){
        $domain_suffix_id = $this->input->get('id', 'intval');

        if(!$domain_suffix_id){
            Response::notify('error', '未指定域名后缀ID');
        }

        $domain_suffix = BaikeDomainSuffixesTable::model()->find($domain_suffix_id);
        if(!$domain_suffix){
            Response::notify('error', "指定域名后缀ID[{$domain_suffix_id}]不存在");
        }

        Response::json(array(
            'domain_suffix'=>$domain_suffix
        ));
    }
    
    public function isSuffixNotExist(){
        $suffix = $this->input->request('suffix', 'trim');
        if(!$suffix){
            Response::json('', 0, '域名后缀不能为空');
        }
        
        if(substr($suffix, 0, 1) != '.'){
            //自动加上点前缀
            $suffix = '.' . $suffix;
        }
        
        if(BaikeDomainSuffixesTable::model()->fetchRow(array(
            'suffix = ?'=>$suffix,
            'id != ?'=>$this->input->request('id', 'intval', false),
        ))){
            Response::json('', 0, '域名后缀已存在');
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
            BaikeDomainSuffixesTable::model()->update(array(
                'sort'=>$i,
            ), $s);
        }

        Response::notify('success', '排序保存成功');
    }
}