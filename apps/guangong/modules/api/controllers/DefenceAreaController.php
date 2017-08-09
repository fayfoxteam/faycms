<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use cms\services\OptionService;
use fay\core\Response;
use cms\services\file\FileService;
use guangong\models\tables\GuangongDefenceAreasTable;
use guangong\models\tables\GuangongUserExtraTable;

class DefenceAreaController extends ApiController{
    /**
     * 防区列表
     */
    public function listAction(){
        $areas = GuangongDefenceAreasTable::model()->fetchAll(
            array('enabled = 1'),
            'id,name,picture',
            'sort, id DESC'
        );
        
        foreach($areas as $k => $area){
            $areas[$k]['picture'] = FileService::get(
                $area['picture'],
                array(
                    'spare'=>'none',
                ),
                'id,url'
            );
        }
        
        return Response::json($areas);
    }
    
    /**
     * 选定防区（随机）
     */
    public function set(){
        //登录检查
        $this->checkLogin();
        
        $userExtra = GuangongUserExtraTable::model()->find($this->current_user, 'defence_area_id,military');
        
        if($userExtra['military'] < OptionService::get('guangong:junfei', 1100)){
            Response::notify('error', array(
                'message'=>'您还未完成注册，请加入关羽军团后继续体验。',
                'code'=>'recruit-first'
            ));
        }
        
//        if($userExtra['defence_area_id']){
//            Response::notify('error', array(
//                'message'=>'您已设置过防区，不能重复设置',
//                'code'=>'arm-already-set'
//            ));
//        }
        
        //随机一个防区
        $area = GuangongDefenceAreasTable::model()->fetchRow('enabled = 1', '*', 'RAND()');

        $area['picture'] = FileService::service()->get($area['picture']);
        $area['text_picture'] = FileService::service()->get($area['text_picture']);
        
        GuangongUserExtraTable::model()->update(array(
            'defence_area_id'=>$area['id'],
        ), array(
            'user_id = ?'=>$this->current_user
        ));
        
        Response::notify('success', array(
            'message'=>'防区设置成功',
            'data'=>$area,
        ));
    }
}