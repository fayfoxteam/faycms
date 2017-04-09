<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\services\file\FileService;
use guangong\models\tables\GuangongArmsTable;
use guangong\models\tables\GuangongUserExtraTable;

class ArmController extends ApiController{
    /**
     * 兵种列表
     */
    public function listAction(){
        $arms = GuangongArmsTable::model()->fetchAll(
            array('enabled = 1'),
            'id,name,picture',
            'sort, id DESC'
        );
        
        foreach($arms as $k => $arm){
            $arms[$k]['picture'] = FileService::get(
                $arm['picture'],
                array(
                    'spare'=>'none',
                ),
                'id,url'
            );
        }
        
        Response::json($arms);
    }
    
    /**
     * 选定兵种（随机）
     */
    public function set(){
        //登录检查
        $this->checkLogin();
        
//        $userExtra = GuangongUserExtraTable::model()->find($this->current_user, 'arm_id');
//        if($userExtra['arm_id']){
//            Response::notify('error', array(
//                'message'=>'您已设置过兵种，不能重复设置',
//                'code'=>'arm-already-set'
//            ));
//        }
        
        //随机一个兵种
        $arm = GuangongArmsTable::model()->fetchRow('enabled = 1', '*', 'RAND()');
        
        GuangongUserExtraTable::model()->update(array(
            'arm_id'=>$arm['id'],
        ), array(
            'user_id = ?'=>$this->current_user
        ));
        
        $arm['picture'] = FileService::service()->get($arm['picture']);
        $arm['description_picture'] = FileService::service()->get($arm['description_picture']);
        Response::notify('success', array(
            'message'=>'兵种设置成功',
            'data'=>$arm,
        ));
    }
}