<?php
namespace guangong\modules\frontend\controllers;

use cms\services\file\FileService;
use cms\services\OptionService;
use cms\services\user\UserService;
use cms\services\wechat\jssdk\JsSDK;
use guangong\library\FrontController;
use guangong\models\tables\GuangongArmsTable;
use guangong\models\tables\GuangongDefenceAreasTable;
use guangong\models\tables\GuangongHoursTable;
use guangong\models\tables\GuangongUserExtraTable;
use guangong\services\AttendanceService;
use guangong\services\PostService;

/**
 * 网络体验
 */
class ArmController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->title = '网络体验';
    }
    
    public function index(){
        if($this->checkLogin()){
            $user = UserService::service()->get($this->current_user, 'nickname');
            $user_extra = GuangongUserExtraTable::model()->find($this->current_user);
            if(!$user_extra['arm_id'] || !$user_extra['defence_area_id'] || !$user_extra['hour_id']){
                //有一项没有完成，就要重新来过
                GuangongUserExtraTable::model()->update(array(
                    'arm_id'=>0,
                    'defence_area_id'=>0,
                    'hour_id'=>0,
                ), array(
                    'user_id = ?'=>$this->current_user
                ));
                
                $user_extra['arm_id'] = 0;
                $user_extra['defence_area_id'] = 0;
                $user_extra['hour_id'] = 0;
            }
            
            $defence = $user_extra['defence_area_id'] ? GuangongDefenceAreasTable::model()->find($user_extra['defence_area_id']) : array();
            $arm = $user_extra['arm_id'] ? GuangongArmsTable::model()->find($user_extra['arm_id']) : array();
            if($arm){
                $arm['picture'] = FileService::service()->get($arm['picture']);
                $arm['description_picture'] = FileService::service()->get($arm['description_picture']);
            }
            $hour = $user_extra['hour_id'] ? GuangongHoursTable::model()->find($user_extra['hour_id']) : array();
            
            $sign_up_days = ceil(($this->current_time - $user_extra['sign_up_time']) / 86400);
            $attendances = AttendanceService::service()->getCount();
        }else{
            $user = $user_extra = $defence = $arm = $hour = array();
            $sign_up_days = $attendances = 0;
        }
        
        $app_config = OptionService::getGroup('oauth:weixin');
        
        $js_sdk = new JsSDK($app_config['app_id'], $app_config['app_secret']);
        
        $this->view->assign(array(
            'user'=>$user,
            'user_extra'=>$user_extra,
            'defence'=>$defence,
            'arm'=>$arm,
            'hour'=>$hour,
            'sign_up_days'=>$sign_up_days,
            'attendances'=>$attendances,
            'next_post'=>PostService::service()->getNextPost(),
            'js_sdk_config'=>$js_sdk->getConfig(array('onMenuShareTimeline')),
        ))->render();
    }
    
    /**
     * 定防区
     */
    public function setDefence(){
        $this->layout->title = '定防区';
        
        $user_extra = GuangongUserExtraTable::model()->find($this->current_user, 'defence_area_id');
        if($user_extra['defence_area_id']){
            $defence = GuangongDefenceAreasTable::model()->find($user_extra['defence_area_id']);
            $this->view->defence = $defence;
        }else{
            $this->view->defence = array();
        }
        $this->view->render();
    }
    
    /**
     * 选兵种
     */
    public function setArm(){
        $this->layout->title = '选兵种';
        
        $user_extra = GuangongUserExtraTable::model()->find($this->current_user, 'arm_id');
        if($user_extra['arm_id']){
            $arm = GuangongArmsTable::model()->find($user_extra['arm_id']);
            $arm['picture'] = FileService::service()->get($arm['picture']);
            $arm['description_picture'] = FileService::service()->get($arm['description_picture']);
            $this->view->arm = $arm;
        }else{
            $this->view->arm = array();
        }
        $this->view->render();
    }
    
    /**
     * 排勤务
     */
    public function setHour(){
        $this->layout->title = '排勤务';
        
        $user_extra = GuangongUserExtraTable::model()->find($this->current_user, 'hour_id');
        if($user_extra['hour_id']){
            $this->view->hour = GuangongHoursTable::model()->find($user_extra['hour_id']);
        }else{
            $this->view->hour = array();
        }
        $this->view->render();
    }
    
    /**
     * 录军籍
     */
    public function info(){
        $this->layout->title = '录军籍';
        
        
        $this->view->render();
    }
    
    /**
     * 履军职
     */
    public function job(){
        $this->layout->title = '履军职';
        
        
        $this->view->render();
    }
}