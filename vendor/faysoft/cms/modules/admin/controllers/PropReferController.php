<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\HttpException;
use fay\core\Sql;

/**
 * 自定义属性关联管理
 */
class PropReferController extends AdminController{
    public function index(){
        $refer = $this->input->get('refer', 'intval');
        $type = $this->input->get('type', 'intval');

        if(!$refer){
            throw new HttpException('refer参数不能为空');
        }
        if(!$type){
            throw new HttpException('type参数不能为空');
        }
        
        $sql = new Sql();
        $props = $sql->from(array('pr'=>'props_refers'), '')
            ->joinLeft(array('p'=>'props'), 'pr.prop_id = p.id', '*')
            ->where('pr.refer = ?', $refer)
            ->where('p.type = ?', $type)
            ->fetchAll();
        
        $this->view->assign(array(
            'props'=>$props,
        ))->render();
    }
}