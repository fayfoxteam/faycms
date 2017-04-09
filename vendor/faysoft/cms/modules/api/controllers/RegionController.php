<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use cms\models\tables\RegionsTable;

/**
 * 地区选择
 */
class RegionController extends ApiController{
    /**
     * 根据传入ID，获取它的下一级地区列表
     * @parameter int $id
     */
    public function getNextLevel(){
        //验证必须get方式发起请求
        $this->checkMethod('GET');
        
        //表单验证
        $this->form()->setRules(array(
            array(array('id'), 'required'),
            array(array('id'), 'int', array('min'=>1)),
            array(array('id'), 'exist', array(
                'table'=>'regions',
                'field'=>'id',
            )),
        ))->setFilters(array(
            'id'=>'intval',
        ))->setLabels(array(
            'id'=>'地区ID',
        ))->check();
        
        $id = $this->form()->getData('id');
        
        $regions = RegionsTable::model()->fetchAll(array(
            'parent_id = ?'=>$id
        ), 'id,name');
        
        Response::json(array(
            'regions'=>$regions,
        ));
    }
}