<?php
namespace faywiki\modules\api\controllers;

use cms\library\UserController;
use fay\core\Response;
use fay\helpers\FieldItem;
use faywiki\services\doc\DocFavoriteService;
use faywiki\services\doc\DocService;

/**
 * 文档收藏
 */
class DocFavoriteController extends UserController{
    /**
     * 收藏
     * @parameter int $doc_id 文档ID
     * @parameter string $trackid 追踪ID
     */
    public function add(){
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

        if(DocFavoriteService::isFavorited($doc_id)){
            Response::notify('error', array(
                'message'=>'您已收藏过该文档',
                'code'=>'already-favorited',
            ));
        }

        DocFavoriteService::add($doc_id, $this->form()->getData('trackid', ''));

        Response::notify('success', '收藏成功');
    }

    /**
     * 取消收藏
     * @parameter int $doc_id 文档ID
     */
    public function remove(){
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

        if(!DocFavoriteService::isFavorited($doc_id)){
            Response::notify('error', array(
                'message'=>'您未收藏过该文档',
                'code'=>'not-favorited',
            ));
        }

        DocFavoriteService::remove($doc_id);

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

        $fields = new FieldItem(
            $this->form()->getData('fields', DocService::$default_fields),
            'doc',
            DocService::$public_fields
        );

        $favorites = DocFavoriteService::service()->getList(
            $fields,
            $this->form()->getData('page', 1),
            $this->form()->getData('page_size', 20)
        );
        Response::json($favorites);
    }
}