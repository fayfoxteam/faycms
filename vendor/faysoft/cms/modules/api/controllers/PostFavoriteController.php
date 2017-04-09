<?php
namespace cms\modules\api\controllers;

use cms\library\UserController;
use cms\services\post\PostFavoriteService;
use fay\core\Response;
use cms\services\post\PostService;
use fay\helpers\FieldHelper;

/**
 * 文章收藏
 */
class PostFavoriteController extends UserController{
    /**
     * 收藏
     * @parameter int $post_id 文章ID
     * @parameter string $trackid 追踪ID
     */
    public function add(){
        //表单验证
        $this->form()->setRules(array(
            array(array('post_id'), 'required'),
            array('post_id', 'int', array('min'=>1)),
        ))->setFilters(array(
            'post_id'=>'intval',
            'trackid'=>'trim',
        ))->setLabels(array(
            'post_id'=>'文章ID',
        ))->check();
        
        $post_id = $this->form()->getData('post_id');
        
        if(!PostService::isPostIdExist($post_id)){
            Response::notify('error', array(
                'message'=>'文章ID不存在',
                'code'=>'invalid-parameter:post_id-is-not-exist',
            ));
        }
        
        if(PostFavoriteService::isFavorited($post_id)){
            Response::notify('error', array(
                'message'=>'您已收藏过该文章',
                'code'=>'already-favorited',
            ));
        }
        
        PostFavoriteService::add($post_id, $this->form()->getData('trackid', ''));
        
        Response::notify('success', '收藏成功');
    }
    
    /**
     * 取消收藏
     * @parameter int $post_id 文章ID
     */
    public function remove(){
        //表单验证
        $this->form()->setRules(array(
            array(array('post_id'), 'required'),
            array('post_id', 'int', array('min'=>1)),
        ))->setFilters(array(
            'post_id'=>'intval',
        ))->setLabels(array(
            'post_id'=>'文章ID',
        ))->check();
        
        $post_id = $this->form()->getData('post_id');
        
        if(!PostFavoriteService::isFavorited($post_id)){
            Response::notify('error', array(
                'message'=>'您未收藏过该文章',
                'code'=>'not-favorited',
            ));
        }
        
        PostFavoriteService::remove($post_id);
        
        Response::notify('success', '移除收藏成功');
    }
    
    /**
     * 收藏列表
     * @parameter string $fields 字段
     * @parameter int $page 页码
     * @parameter int $page_size 分页大小
     */
    public function listAction(){
        //验证必须get方式发起请求
        $this->checkMethod('GET');
        
        //表单验证
        $this->form()->setRules(array(
            array(array('page', 'page_size'), 'int', array('min'=>1)),
            array('fields', 'fields'),
        ))->setFilters(array(
            'page'=>'intval',
            'page_size'=>'intval',
            'fields'=>'trim',
        ))->setLabels(array(
            'page'=>'页码',
            'page_size'=>'分页大小',
            'fields'=>'字段',
        ))->check();
        
        $fields = $this->form()->getData('fields');
        if($fields){
            //过滤字段，移除那些不允许的字段
            $fields = FieldHelper::parse($fields, 'post', PostService::$public_fields);
        }else{
            $fields = PostService::$default_fields;
        }
        
        $favorites = PostFavoriteService::service()->getList($fields,
            $this->form()->getData('page', 1),
            $this->form()->getData('page_size', 20));
        Response::json($favorites);
    }
}