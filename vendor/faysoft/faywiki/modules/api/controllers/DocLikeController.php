<?php
namespace faywiki\modules\api\controllers;

use cms\library\ApiController;
use cms\services\user\UserService;
use fay\core\Response;
use fay\helpers\FieldsHelper;
use faywiki\services\doc\DocLikeService;
use faywiki\services\doc\DocService;

/**
 * 文档点赞
 */
class DocLikeController extends ApiController{
    /**
     * 点赞
     * @parameter int $doc_id 文档ID
     * @parameter string $trackid 追踪ID
     */
    public function add(){
        //登录检查
        $this->checkLogin();

        //表单验证
        $this->form()->setRules(array(
            array(array('doc_id'), 'required'),
            array('doc_id', 'int', array('min'=>1)),
        ))->setFilters(array(
            'doc_id'=>'intval',
            'trackid'=>'trim',
        ))->setLabels(array(
            'doc_id'=>'文档ID',
        ))->check();

        $doc_id = $this->form()->getData('doc_id');

        if(!DocService::isDocIdExist($doc_id)){
            Response::notify('error', array(
                'message'=>"指定文档ID[{$doc_id}]不存在",
                'code'=>'invalid-parameter:doc_id-is-not-exist',
            ));
        }

        if(DocLikeService::isLiked($doc_id)){
            Response::notify('error', array(
                'message'=>'您已赞过该文档',
                'code'=>'already-favorited',
            ));
        }

        DocLikeService::add($doc_id, $this->form()->getData('trackid', ''));

        Response::notify('success', '点赞成功');
    }

    /**
     * 取消点赞
     * @parameter int $doc_id 文档ID
     */
    public function remove(){
        //登录检查
        $this->checkLogin();

        //表单验证
        $this->form()->setRules(array(
            array(array('doc_id'), 'required'),
            array('doc_id', 'int', array('min'=>1)),
        ))->setFilters(array(
            'doc_id'=>'intval',
        ))->setLabels(array(
            'doc_id'=>'文档ID',
        ))->check();

        $doc_id = $this->form()->getData('doc_id');

        if(!DocLikeService::isLiked($doc_id)){
            Response::notify('error', array(
                'message'=>'您未赞过该文档',
                'code'=>'not-liked',
            ));
        }

        DocLikeService::remove($doc_id);

        Response::notify('success', '取消点赞成功');
    }

    /**
     * 文档点赞列表
     * @parameter int $doc_id 文档ID
     * @parameter string $fields 字段
     * @parameter int $page 页码
     * @parameter int $page_size 分页大小
     */
    public function listAction(){
        //验证必须get方式发起请求
        $this->checkMethod('GET');

        //表单验证
        $this->form()->setRules(array(
            array(array('doc_id', 'page', 'page_size'), 'int', array('min'=>1)),
            array('fields', 'fields'),
        ))->setFilters(array(
            'doc_id'=>'intval',
            'page'=>'intval',
            'page_size'=>'intval',
            'fields'=>'trim',
        ))->setLabels(array(
            'doc_id'=>'文档ID',
            'page'=>'页码',
            'page_size'=>'分页大小',
            'fields'=>'字段',
        ))->check();

        $doc_id = $this->form()->getData('doc_id');

        if(!DocService::isDocIdExist($doc_id)){
            Response::notify('error', array(
                'message'=>"指定文档ID[{$doc_id}]不存在",
                'code'=>'invalid-parameter:doc_id-is-not-exist',
            ));
        }

        $fields = new FieldsHelper(
            $this->form()->getData('fields', UserService::$default_fields),
            'user',
            UserService::$public_fields
        );

        $likes = DocLikeService::service()->getDocLikes(
            $doc_id,
            $fields,
            $this->form()->getData('page', 1),
            $this->form()->getData('page_size', 20)
        );
        return Response::json($likes);
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
            $this->form()->getData('fields', DocService::$default_fields),
            'doc',
            DocService::$public_fields
        );

        $likes = DocLikeService::service()->getUserLikes(
            $fields,
            $this->form()->getData('page', 1),
            $this->form()->getData('page_size', 20)
        );
        return Response::json($likes);
    }
}