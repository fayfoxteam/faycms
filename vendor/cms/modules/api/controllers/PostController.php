<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\services\Post;
use fay\helpers\FieldHelper;
use fay\core\HttpException;

/**
 * 文章
 */
class PostController extends ApiController{
	/**
	 * 默认返回字段
	 */
	private $default_fields = array(
		'post'=>array(
			'fields'=>array(
				'id', 'title', 'content', 'content_type', 'publish_time', 'thumbnail', 'abstract',
			)
		),
		'category'=>array(
			'fields'=>array(
				'id', 'title', 'alias',
			)
		),
		'user'=>array(
			'fields'=>array(
				'id', 'nickname', 'avatar',
			)
		)
	);
	
	/**
	 * 获取一篇文章
	 * @parameter int $id 文章ID
	 * @parameter string $fields 可指定返回文章字段（只允许Post::$public_fields中的字段）
	 * @parameter int|string $cat 指定分类（可选），若指定分类，则文章若不属于该分类，返回404
	 */
	public function get(){
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
		$fields = $this->form()->getData('fields');
		$cat = $this->form()->getData('cat');
		
		if($fields){
			//过滤字段，移除那些不允许的字段
			$fields = FieldHelper::parse($fields, 'post', Post::$public_fields);
		}else{
			//若未指定$fields，取默认值
			$fields = $this->default_fields;
		}
		
		//post字段若未指定，需要默认下
		if(empty($fields['post'])){
			$fields['post'] = $this->default_fields['post'];
		}
		
		$post = Post::service()->get($id, $fields, $cat);
		if($post){
			Response::json($post);
		}else{
			throw new HttpException('您访问的页面不存在');
		}
	}
	
	public function listAction(){
		
	}
}