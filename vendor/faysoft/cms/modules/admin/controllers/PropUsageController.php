<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\services\prop\PropService;
use fay\core\HttpException;
use fay\core\Sql;

/**
 * 自定义属性关联管理
 */
class PropUsageController extends AdminController{
    public function index(){
        $this->layout->subtitle = '自定义属性用途';
        
        $usage_type = $this->input->get('usage_type', 'intval');
        $usage_id = $this->input->get('usage_id', 'intval');

        if(!$usage_type){
            throw new HttpException('usage_type参数不能为空');
        }
        if(!$usage_id){
            throw new HttpException('usage_id参数不能为空');
        }
        
        $props = PropService::service()->getPropsByUsage($usage_id, $usage_type, array(), false);
        $relation_props = PropService::service()->getPropsByUsage(
            array(),
            $usage_type,
            PropService::service()->getUsageModel($usage_type)->getSharedUsages($usage_id),
            false
        );
        dd($props, $relation_props);
        
        $this->view->assign(array(
            'props'=>$props,
            'relation_props'=>$relation_props,
        ))->render();
    }
}