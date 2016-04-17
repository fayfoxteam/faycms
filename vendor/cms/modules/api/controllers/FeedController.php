<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\services\Feed as FeedService;
use fay\models\tables\Feeds;
use fay\core\Response;
use fay\helpers\FieldHelper;
use fay\core\HttpException;
use fay\models\Feed as FeedModel;

/**
 * 动态
 */
class FeedController extends ApiController{
	/**
	 * 创建一篇动态
	 * @param string $content 动态文本
	 * @param int $files 配图。支持以数组方式传入，或逗号分割的方式传入
	 * @param string $description 图片描述。目前只支持以数组方式传入
	 * @param float $longitude 经度
	 * @param float $latitude 纬度
	 * @param string address 定位地址
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
		
		FeedService::model()->create(array(
			'content'=>$this->form()->getData('content'),
			'address'=>$this->form()->getData('address'),
			'status'=>Feeds::STATUS_PUBLISHED,
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
	 * @param int $id 动态ID
	 * @param string $fields 可指定返回动态字段（只允许$this->allowed_fields中的字段）
	 * @param int|string $cat 指定分类（可选），若指定分类，则动态若不属于该分类，返回404
	 */
	public function get(){
		//表单验证
		$this->form()->setRules(array(
			array(array('id'), 'required'),
			array(array('id'), 'int', array('min'=>1)),
		))->setFilters(array(
			'id'=>'intval',
			'fields'=>'trim',
			'cat'=>'trim',
		))->setLabels(array(
			'id'=>'动态ID',
		))->check();
		
		$id = $this->form()->getData('id');
		$fields = $this->form()->getData('fields');
		$cat = $this->form()->getData('cat');
		
		if($fields){
			//过滤字段，移除那些不允许的字段
			$fields = FieldHelper::process($fields, 'feed', FeedModel::$public_fields);
		}else{
			//若未指定$fields，取默认值
			$fields = $this->default_fields;
		}
		
		//post字段若未指定，需要默认下
		if(empty($fields['post'])){
			$fields['post'] = $this->default_fields['post'];
		}
		
		$feed = FeedModel::model()->get($id, $fields, $cat);
		if($feed){
			Response::json($feed);
		}else{
			throw new HttpException('您访问的页面不存在');
		}
	}
}