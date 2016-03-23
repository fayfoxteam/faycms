<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\services\Feed;
use fay\models\tables\Feeds;
use fay\core\Response;

/**
 * 动态
 */
class FeedController extends ApiController{
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
			'post_id'=>'文章ID',
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
		
		Feed::model()->create(array(
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
}