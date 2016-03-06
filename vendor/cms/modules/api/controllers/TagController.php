<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\models\Tag;

class TagController extends ApiController{
	/**
	 * 标签列表
	 * @param string $type 类型
	 * @param string $order 排序方式
	 * @param int $page 页码
	 * @param int $page_size 分页大小
	 */
	public function listAction(){
		if($this->form()->setRules(array(
			array('type', 'range', array('range'=>array('feed', 'post'))),
			array(array('page', 'page_size'), 'int', array('min'=>1)),
		))->setFilters(array(
			'type'=>'trim',
			'page'=>'intval',
			'page_size'=>'intval',
			'order'=>'trim',
		))->setLabels(array(
			'type'=>'类型',
			'page'=>'页码',
			'page_size'=>'分页大小',
			'order'=>'排序方式',
		))->check()){
			$type = $this->form()->getData('type', 'post');
			$order = $this->form()->getData('order', 'count');
			
			switch($order){
				case 'create_time':
					$order = 'create_time DESC';
					break;
				case 'hand':
					$order = 'sort, {$type}_count DESC';
					break;
				case 'count':
				default:
					$order = '{$type}_count DESC';
			}
			
			return Response::json(Tag::model()->getList(
				$type,
				$this->form()->getData('page_size', 20),
				$this->form()->getData('page', 1),
				$order
			));
		}else{
			$error = $this->form()->getFirstError();
			Response::notify('error', array(
				'message'=>$error['message'],
				'code'=>$error['code'],
			));
		}
	}
	
	/**
	 * 判断标签是否存在
	 * @param string $tag 标签
	 */
	public function isTagExist(){
		if($this->form()->setRules(array(
			array('tag', 'required'),
		))->setFilters(array(
			'tag'=>'trim',
		))->setLabels(array(
			'tag'=>'标签',
		))->check()){
			if(Tag::isTagExist($this->form()->getData('tag'))){
				Response::notify('error', '标签已存在');
			}else{
				Response::notify('success');
			}
		}else{
			$error = $this->form()->getFirstError();
			Response::notify('error', array(
				'message'=>$error['message'],
				'code'=>$error['code'],
			));
		}
	}
}