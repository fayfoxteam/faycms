<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use cms\services\post\PostLikeService;
use cms\services\post\PostService;
use cms\services\user\UserService;
use fay\core\JsonResponse;
use fay\core\Response;
use fay\helpers\FieldsHelper;

/**
 * 文章点赞
 */
class PostLikeController extends ApiController{
    /**
     * 点赞
     * @parameter int $post_id 文章ID
     * @parameter string $trackid 追踪ID
     */
    public function add(){
        //登录检查
        $this->checkLogin();
        
        //表单验证
        $this->form()->setRules(array(
            array(array('post_id'), 'required'),
            array('post_id', 'cms\validators\PostIDValidator'),
        ))->setFilters(array(
            'post_id'=>'intval',
            'trackid'=>'trim',
        ))->setLabels(array(
            'post_id'=>'文章ID',
        ))->check();
        
        $post_id = $this->form()->getData('post_id');
        
        if(PostLikeService::isLiked($post_id)){
            return Response::notify(Response::NOTIFY_FAIL, array(
                'message'=>'您已赞过该文章',
                'code'=>'already-favorited',
            ));
        }
        
        PostLikeService::add($post_id, $this->form()->getData('trackid', ''));
        
        return Response::notify(Response::NOTIFY_SUCCESS, '点赞成功');
    }
    
    /**
     * 取消点赞
     * @parameter int $post_id 文章ID
     */
    public function remove(){
        //登录检查
        $this->checkLogin();
        
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
        
        if(!PostLikeService::isLiked($post_id)){
            return Response::notify(Response::NOTIFY_FAIL, array(
                'message'=>'您未赞过该文章',
                'code'=>'not-liked',
            ));
        }
        
        PostLikeService::remove($post_id);
        
        return Response::notify(Response::NOTIFY_SUCCESS, '取消点赞成功');
    }
    
    /**
     * 文章点赞列表
     * @parameter int $post_id 文章ID
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
            array('post_id', 'cms\validators\PostIDValidator'),
            array('fields', 'fields'),
        ))->setFilters(array(
            'post_id'=>'intval',
            'page'=>'intval',
            'page_size'=>'intval',
            'fields'=>'trim',
        ))->setLabels(array(
            'post_id'=>'文章ID',
            'page'=>'页码',
            'page_size'=>'分页大小',
            'fields'=>'字段',
        ))->check();
        
        $post_id = $this->form()->getData('post_id');
        
        if(!PostService::isPostIdExist($post_id)){
            return Response::notify(Response::NOTIFY_FAIL, array(
                'message'=>'文章ID不存在',
                'code'=>'invalid-parameter:post_id-is-not-exist',
            ));
        }
        
        $fields = new FieldsHelper(
            $this->form()->getData('fields', UserService::$default_fields),
            'user',
            UserService::$public_fields
        );
        
        $likes = PostLikeService::service()->getPostLikes(
            $post_id,
            $fields,
            $this->form()->getData('page', 1),
            $this->form()->getData('page_size', 20)
        );
        return new JsonResponse($likes);
    }
    
    /**
     * 我的点赞列表（api不支持获取别人的点赞列表）
     * @parameter string $fields 字段
     * @parameter int $page 页码
     * @parameter int $page_size 分页大小
     */
    public function userLikes(){
        //验证必须get方式发起请求
        $this->checkMethod('GET');
        
        //登录检查
        $this->checkLogin();
        
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
        
        $fields = new FieldsHelper(
            $this->form()->getData('fields', PostService::$default_fields),
            'doc',
            PostService::$public_fields
        );
        
        $likes = PostLikeService::service()->getUserLikes(
            $fields,
            $this->form()->getData('page', 1),
            $this->form()->getData('page_size', 20)
        );
        return new JsonResponse($likes);
    }
}