<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\services\prop\PropService;
use fay\core\HttpException;
use fay\core\Sql;
use fay\helpers\ArrayHelper;

/**
 * 自定义属性关联管理
 */
class PropUsageController extends AdminController{
    public function index(){
        $usage_type = $this->input->get('usage_type', 'intval');
        $usage_id = $this->input->get('usage_id', 'intval');

        if(!$usage_type){
            throw new HttpException('usage_type参数不能为空');
        }
        if(!$usage_id){
            throw new HttpException('usage_id参数不能为空');
        }
        
        $this->form()->setRules(array(
            array('sort', 'int', array('min'=>0, 'max'=>65535))
        ))->setLabels(array(
            'sort'=>'排序值',
        ));

        $usage_model = PropService::service()->getUsageModel($usage_type);
        $this->layout->subtitle = '自定义属性 - ' .
            $usage_model->getUsageName() . ' - ' .
            $usage_model->getUsageItemTitle($usage_id)
        ;
        
        $sql = new Sql();
        $props = $sql->from(array('pu'=>'props_usages'), array('is_share', 'sort'))
            ->joinLeft(array('p'=>'props'), 'pu.prop_id = p.id', '*')
            ->where('pu.usage_id = ?', $usage_id)
            ->where('p.usage_type = ?', $usage_type)
            ->fetchAll();
        
        $shared_usages = $usage_model->getSharedUsages($usage_id);
        if($shared_usages){
            $relation_props = $sql->from(array('pu'=>'props_usages'), array('is_share', 'sort', 'usage_id'))
                ->joinLeft(array('p'=>'props'), 'pu.prop_id = p.id', '*')
                ->where('pu.usage_id IN (?)', $shared_usages)
                ->where('pu.is_share = 1')
                ->where('p.usage_type = ?', $usage_type)
                ->where('p.id NOT IN (?)', ArrayHelper::column($props, 'id'))
                ->fetchAll();
        }else{
            $relation_props = array();
        }
        
        $this->view->assign(array(
            'props'=>$props,
            'relation_props'=>$relation_props,
            'usage_model'=>$usage_model,
            'usage_type'=>$usage_type,
            'usage_id'=>$usage_id,
        ))->render();
    }
    
    public function edit(){
        $this->form()->setRules(array(
            array('sort', 'int', array('min'=>0, 'max'=>65535)),
            array(array('usage_id'), 'int'),
        ))->setLabels(array(
            'sort'=>'排序值',
        ))->setFilters(array(
            'usage_id'=>'intval',
            'sort'=>'intval',
        ))->check();

        $usage_id = $this->form()->getData('usage_id');
        $sort = $this->form()->getData('sort');
        
        
    }
}