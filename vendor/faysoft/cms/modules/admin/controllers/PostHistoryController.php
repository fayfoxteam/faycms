<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\services\CategoryService;
use cms\services\post\PostHistoryService;
use cms\services\user\UserService;
use fay\core\HttpException;
use fay\core\Response;
use fay\helpers\ArrayHelper;

class PostHistoryController extends AdminController{
    /**
     * 获取历史记录列表
     * @parameter int $post_id
     */
    public function listAction(){
        //验证必须get方式发起请求
        $this->checkMethod('GET');
    
        //表单验证
        $this->form()->setRules(array(
            array(array('post_id'), 'required'),
            array(array('post_id', 'page_size'), 'int', array('min'=>1)),
            array(array('last_id'), 'int', array('min'=>0)),
            array('fields', 'fields'),
        ))->setFilters(array(
            'post_id'=>'intval',
            'fields'=>'trim',
            'page_size'=>'intval',
            'last_id'=>'intval',
        ))->setLabels(array(
            'post_id'=>'文章ID',
            'fields'=>'字段',
            'page_size'=>'分页大小',
            'last_id'=>'起始ID',
        ))->check();
        
        $histories = PostHistoryService::service()->getPostHistory(
            $this->form()->getData('post_id'),
            $this->form()->getData('fields', 'id,user_id,create_time'),
            $this->form()->getData('page_size', 10),
            $this->form()->getData('last_id', 0)
        );
        
        $user_map = UserService::service()->mget(ArrayHelper::column($histories, 'user_id'), 'id,nickname,username');
        foreach($histories as &$history){
            $history['user'] = $user_map[$history['user_id']];
            unset($history['user_id']);
        }
        
        Response::json(array(
            'histories'=>$histories
        ));
    }
    
    /**
     * 恢复至指定历史版本
     * @parameter int $history_id
     */
    public function revert(){
        //表单验证
        $this->form()->setRules(array(
            array(array('history_id'), 'required'),
            array(array('history_id'), 'int', array('min'=>1)),
        ))->setFilters(array(
            'history_id'=>'intval',
        ))->setLabels(array(
            'history_id'=>'历史版本ID',
        ))->check();
    
        PostHistoryService::service()->revert($this->form()->getData('history_id'));
        
        Response::notify('success', '恢复成功');
    }
    
    /**
     * 展示一个历史（相当于文章预览）
     * @parameter int $history_id
     */
    public function item(){
        //表单验证
        $this->form()->setRules(array(
            array(array('history_id'), 'required'),
            array(array('history_id'), 'int', array('min'=>1)),
        ))->setFilters(array(
            'history_id'=>'intval',
        ))->setLabels(array(
            'history_id'=>'历史版本ID',
        ))->check();
        
        $history = PostHistoryService::service()->get($this->form()->getData('history_id'));
        if(!$history){
            throw new HttpException("指定历史版本ID[{$this->form()->getData('history_id')}]不存在");
        }
        
        $history['user'] = UserService::service()->get($history['user_id'], 'id,nickname,username,realname,avatar');
        $history['category'] = CategoryService::service()->get($history['cat_id'], 'title');
        
        $this->view->renderPartial('item', array(
            'history'=>$history,
        ));
    }
}