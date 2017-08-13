<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use cms\services\post\PostService;
use fay\exceptions\NotFoundHttpException;
use fay\core\Response;
use fay\helpers\FieldsHelper;

/**
 * 文章
 */
class PostController extends ApiController{
    /**
     * 默认返回字段
     */
    protected $default_fields = array(
        'post'=>array(
            'id', 'title', 'content', 'content_type', 'publish_time', 'thumbnail', 'abstract',
        ),
        'category'=>array(
            'id', 'title', 'alias',
        ),
        'user'=>array(
            'id', 'nickname', 'avatar',
        )
    );
    
    /**
     * 获取一篇文章
     * @parameter int $id 文章ID
     * @parameter string $fields 可指定返回文章字段（只允许PostService::$public_fields中的字段）
     * @parameter int|string $cat 指定分类（可选），若指定分类，则文章若不属于该分类，返回404
     */
    public function get(){
        //验证必须get方式发起请求
        $this->checkMethod('GET');
        
        //表单验证
        $this->form()->setRules(array(
            array(array('id'), 'required'),
            array(array('id'), 'int', array('min'=>1)),
            array('fields', 'fields'),
        ))->setFilters(array(
            'id'=>'intval',
            'fields'=>'trim',
            'cat'=>'trim',
        ))->setLabels(array(
            'id'=>'文章ID',
            'fields'=>'字段',
        ))->check();
        
        $id = $this->form()->getData('id');
        $cat = $this->form()->getData('cat');
        
        $fields = new FieldsHelper(
            $this->form()->getData('fields', $this->default_fields),
            'post',
            PostService::$public_fields
        );
        
        //post字段若未指定，需要默认下
        if(!$fields->getFields()){
            $fields->setFields($this->default_fields['post']);
        }
        
        $post = PostService::service()->get($id, $fields, $cat);
        if($post){
            return Response::json($post);
        }else{
            throw new NotFoundHttpException('您访问的页面不存在');
        }
    }
    
    public function listAction(){
        
    }
}