<?php
namespace fayfeed\modules\api\controllers;

use cms\library\ApiController;
use fay\helpers\FieldsHelper;
use fayfeed\services\FeedService;
use fayfeed\models\tables\FeedsTable;
use fay\core\Response;
use fay\core\HttpException;

/**
 * 动态
 */
class FeedController extends ApiController{
    /**
     * 默认返回字段
     * @var array
     */
    public $default_fields = array(
        
    );
    
    /**
     * 创建一篇动态
     * @parameter string $content 动态文本
     * @parameter int $files 配图。支持以数组方式传入，或逗号分割的方式传入
     * @parameter string $description 图片描述。目前只支持以数组方式传入
     * @parameter float $longitude 经度
     * @parameter float $latitude 纬度
     * @parameter string $address 定位地址
     */
    public function create(){
        //登录检查
        $this->checkLogin();
        
        //表单验证
        $this->form()->setRules(array(
            array(array('content'), 'required'),
            array(array('files'), 'int'),
            array(array('longitude', 'latitude'), 'float', array('length'=>9, 'decimal'=>6)),
            array(array('address'), 'string', array('max'=>500)),
        ))->setFilters(array(
            'post_id'=>'intval',
            'content'=>'trim',
            'files'=>'trim',
            'longitude'=>'floatval',
            'latitude'=>'floatval',
            'address'=>'trim',
        ))->setLabels(array(
            'post_id'=>'动态ID',
            'content'=>'评论内容',
            'files'=>'配图',
            'longitude'=>'经度',
            'latitude'=>'纬度',
            'address'=>'地址',
        ))->check();
        
        //附件
        $files = $this->form()->getData('files', array());
        if(is_string($files)){
            //文件ID串支持以逗号分割的ID串传入
            $files = explode(',', $files);
        }
        $description = $this->form()->getData('description', array());
        $extra_files = array();
        foreach($files as $f){
            $extra_files[$f] = isset($description[$f]) ? $description[$f] : '';
        }
        
        FeedService::service()->create(array(
            'content'=>$this->form()->getData('content'),
            'address'=>$this->form()->getData('address'),
            'status'=>FeedsTable::STATUS_APPROVED,
        ), array(
            'extra'=>array(
                'longitude'=>$this->form()->getData('longitude', '0'),
                'latitude'=>$this->form()->getData('latitude', '0'),
            ),
            'tags'=>$this->form()->getData('tags', ''),
            'files'=>$extra_files,
        ));
        
        Response::notify('success', array(
            'message'=>'发布成功',
            'data'=>array(),
        ));
    }
    
    /**
     * 获取一篇动态
     * @parameter int $id 动态ID
     * @parameter string $fields 可指定返回动态字段（只允许$this->allowed_fields中的字段）
     * @parameter int|string $cat 指定分类（可选），若指定分类，则动态若不属于该分类，返回404
     */
    public function get(){
        //表单验证
        $this->form()->setRules(array(
            array(array('feed_id'), 'required'),
            array(array('feed_id'), 'int', array('min'=>1)),
            array('fields', 'fields'),
        ))->setFilters(array(
            'feed_id'=>'intval',
            'fields'=>'trim',
        ))->setLabels(array(
            'feed_id'=>'动态ID',
            'fields'=>'字段',
        ))->check();
        
        $feed_id = $this->form()->getData('feed_id');

        $fields = new FieldsHelper(
            $this->form()->getData('fields', $this->default_fields),
            'feed',
            FeedService::$public_fields
        );
        
        $feed = FeedService::service()->get($feed_id, $fields, true);
        if($feed){
            Response::json($feed);
        }else{
            throw new HttpException('您访问的页面不存在');
        }
    }
    
    public function delete(){
        //表单验证
        $this->form()->setRules(array(
            array(array('feed_id'), 'required'),
            array(array('feed_id'), 'int', array('min'=>1)),
            array(array('feed_id'), 'exist', array(
                'table'=>'feeds',
                'field'=>'id',
                'conditions'=>array('delete_time = 0')
            )),
        ))->setFilters(array(
            'feed_id'=>'intval',
        ))->setLabels(array(
            'feed_id'=>'动态',
        ))->check();
        
        $feed_id = $this->form()->getData('feed_id');
        
        if(FeedService::service()->checkDeletePermission($feed_id)){
            FeedService::service()->delete($feed_id);
            Response::notify('success', '动态删除成功');
        }else{
            Response::notify('error', array(
                'message'=>'您无权操作该动态',
                'code'=>'permission-denied',
            ));
        }
    }
}