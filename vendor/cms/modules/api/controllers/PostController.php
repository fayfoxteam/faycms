<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\models\Post;
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
	 * 显示一篇文章
	 * @param int $id 文章ID
	 * @param string $fields 可指定返回文章字段（只允许$this->allowed_fields中的字段）
	 * @param int|string $cat 指定分类（可选），若指定分类，则文章若不属于该分类，返回404
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
			'id'=>'文章ID',
		))->check();
		
		$id = $this->form()->getData('id');
		$fields = $this->form()->getData('fields');
		$cat = $this->form()->getData('cat');
		
		if($fields){
			//过滤字段，移除那些不允许的字段
			$fields = FieldHelper::process($fields, 'post', Post::$allowed_fields);
		}else{
			//若未指定$fields，取默认值
			$fields = $this->default_fields;
		}
		
		//post字段若未指定，需要默认下
		if(empty($fields['post'])){
			$fields['post'] = $this->default_fields['post'];
		}
		
		$post = Post::model()->get($id, $fields, $cat);
		if($post){
			Response::json($post);
		}else{
			throw new HttpException('您访问的页面不存在');
		}
	}
	
	public function listAction(){
		
	}
}