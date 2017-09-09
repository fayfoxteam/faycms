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
        
        return $this->view->assign(array(
            'domain_suffixes'=>BaikeDomainSuffixesTable::model()->fetchAll(array(), '*', 'sort')
        ))->render();
    }

    /**
     * 创建一个域名后缀
     */
    public function create(){
        if($this->input->post()){
            if($this->form()->setModel(BaikeDomainSuffixesTable::model())->check()){
                $data = $this->form()->getFilteredData();
                $data['create_time'] = $this->current_time;
                $data['update_time'] = $this->current_time;
                BaikeDomainSuffixesTable::model()->insert($data);

                Response::notify(Response::NOTIFY_SUCCESS, array(
                    'message'=>'域名后缀添加成功',
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

    /**
     * 编辑一个域名后缀
     */
    public function edit(){
        $domain_suffix_id = $this->input->request('id', 'intval');

        if(!$domain_suffix_id){
            Response::notify(Response::NOTIFY_FAIL, '未指定域名后缀ID');
        }

        $domain_suffix = BaikeDomainSuffixesTable::model()->find($domain_suffix_id);
        if(!$domain_suffix){
            Response::notify(Response::NOTIFY_FAIL, "指定域名后缀ID[{$domain_suffix_id}]不存在");
        }

        $this->form()->setModel(BaikeDomainSuffixesTable::model());
        if($this->input->post()){
            if($this->form()->check()){
                $data = $this->form()->getFilteredData();
                if(ArrayHelper::equal($data, $domain_suffix)){
                    Response::notify(Response::NOTIFY_SUCCESS, '没有字段被修改');
                }
                $data['update_time'] = $this->current_time;
                BaikeDomainSuffixesTable::model()->update($data, $domain_suffix_id);
    
                Response::notify(Response::NOTIFY_SUCCESS, '一个域名后缀被编辑');
            }else{
                Response::goback();
            }
        }else{
            Response::notify(Response::NOTIFY_FAIL, array(
                'message'=>'不完整的请求',
            ));
        }
    }

    /**
     * 物理删除一个域名后缀
     */
    public function remove(){
        $domain_suffix_id = $this->input->get('id', 'intval');

        if(!$domain_suffix_id){
            Response::notify(Response::NOTIFY_FAIL, '未指定域名后缀ID');
        }

        $domain_suffix = BaikeDomainSuffixesTable::model()->find($domain_suffix_id);
        if(!$domain_suffix){
            Response::notify(Response::NOTIFY_FAIL, "指定域名后缀ID[{$domain_suffix_id}]不存在");
        }

        BaikeDomainSuffixesTable::model()->delete($domain_suffix_id);

        Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'一个域名后缀被永久删除',
        ));
    }

    /**
     * 获取一个域名后缀
     */
    public function get(){
        $domain_suffix_id = $this->input->get('id', 'intval');

        if(!$domain_suffix_id){
            Response::notify(Response::NOTIFY_FAIL, '未指定域名后缀ID');
        }

        $domain_suffix = BaikeDomainSuffixesTable::model()->find($domain_suffix_id);
        if(!$domain_suffix){
            Response::notify(Response::NOTIFY_FAIL, "指定域名后缀ID[{$domain_suffix_id}]不存在");
        }

        return Response::json(array(
            'domain_suffix'=>$domain_suffix
        ));
    }

    /**
     * 验证指定域名后缀是否可用
     */
    public function isSuffixNotExist(){
        $suffix = $this->input->request('suffix', 'trim');
        if(!$suffix){
            return Response::json('', 0, '域名后缀不能为空');
        }
        
        if(substr($suffix, 0, 1) != '.'){
            //自动加上点前缀
            $suffix = '.' . $suffix;
        }
        
        if(BaikeDomainSuffixesTable::model()->fetchRow(array(
            'suffix = ?'=>$suffix,
            'id != ?'=>$this->input->request('id', 'intval', false),
        ))){
            return Response::json('', 0, '域名后缀已存在');
        }else{
            return Response::json();
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

        Response::notify(Response::NOTIFY_SUCCESS, '排序保存成功');
    }
}