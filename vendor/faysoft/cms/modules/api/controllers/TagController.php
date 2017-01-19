<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\services\TagService;
use fay\models\tables\TagCounterTable;

/**
 * 标签
 */
class TagController extends ApiController{
	/**
	 * 标签列表
	 * @parameter string $type 类型
	 * @parameter string $order 排序方式
	 * @parameter int $page 页码
	 * @parameter int $page_size 分页大小
	 */
	public function listAction(){
		//验证必须get方式发起请求
		$this->checkMethod('GET');
		
		//表单验证
		$this->form()->setRules(array(
			array('type', 'range', array('range'=>TagCounterTable::model()->getFields(array('tag_id')))),
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
		))->check();
		
		$type = $this->form()->getData('type', 'posts');
		$order = $this->form()->getData('order', 'count');
		
		switch($order){
			case 'create_time':
				$order = 'create_time DESC';
				break;
			case 'hand':
				$order = "t.sort, tc.{$type} DESC";
				break;
			case 'count':
			default:
				$order = "tc.{$type} DESC";
		}
		
		Response::json(TagService::service()->getList(
			$order,
			$this->form()->getData('page_size', 20),
			$this->form()->getData('page', 1)
		));
	}
	
	/**
	 * 判断标签是否可用
	 * 可用返回状态为1，不可用返回0，http状态码均为200
	 * @parameter string $tag 标签
	 * @parameter int $id 标签ID。若传入ID，则排除验证改ID对应的标签
	 */
	public function isTagNotExist(){
		//表单验证
		$this->form()->setRules(array(
			array('tag', 'required'),
			array('id', 'int', array('min'=>1)),
		))->setFilters(array(
			'tag'=>'trim',
			'id'=>'intval',
		))->setLabels(array(
			'tag'=>'标签',
			'id'=>'ID'
		))->check();
		
		$conditions = array();
		$id = $this->form()->getData('id');
		if($id){
			$conditions = array(
				'id != ?'=>$id,
			);
		}
		if(TagService::isTagExist($this->form()->getData('tag'), $conditions)){
			Response::json('', 0, '标签已存在');
		}else{
			Response::json();
		}
	}
	
	/**
	 * 判断标签是否存在
	 * 存在返回状态为1，不存在返回0，http状态码均为200
	 * @parameter string $tag 标签
	 */
	public function isTagExist(){
		//表单验证
		$this->form()->setRules(array(
			array('tag', 'required'),
		))->setFilters(array(
			'tag'=>'trim',
		))->setLabels(array(
			'tag'=>'标签',
		))->check();
		
		if(TagService::isTagExist($this->form()->getData('tag'))){
			Response::json();
		}else{
			Response::json('', 0, '标签不存在');
		}
	}
}