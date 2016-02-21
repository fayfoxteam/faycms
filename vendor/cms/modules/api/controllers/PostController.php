<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\models\Post;
use fay\helpers\FieldHelper;
use fay\core\HttpException;

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
	 * 可选字段
	 */
	private $allowed_fields = array(
		'post'=>array(
			'id', 'title', 'content', 'content_type', 'publish_time', 'thumbnail', 'abstract', 'seo_title', 'seo_keywords', 'seo_description',
		),
		'category'=>array(
			'id', 'title', 'alias',
		),
		'categories'=>array(
			'id', 'title', 'alias',
		),
		'user'=>array(
			'id', 'username', 'nickname', 'avatar', 'roles.id', 'roles.title',
		),
		'nav'=>array(
			'id', 'title',
		),
		'tags'=>array(
			'id', 'title',
		),
		'files'=>array(
			'file_id', 'description', 'is_image',
		),
		'props'=>array(
			'*',//这里指定的是属性别名，取值视后台设定而定
		),
		'meta'=>array(
			'comments', 'views', 'likes',
		),
	);
	
	/**
	 * 显示一篇文章
	 * @param int $id 文章ID
	 * @param string $fields 可指定返回文章字段（只允许$this->allowed_fields中的字段）
	 * @param int|string $cat 指定分类（可选），若指定分类，则文章若不属于该分类，返回404
	 */
	public function item(){
		if($this->form()->setRules(array(
			array(array('id'), 'required'),
			array(array('id'), 'int', array('min'=>1)),
		))->setFilters(array(
			'id'=>'intval',
			'fields'=>'trim',
			'cat'=>'trim',
		))->setLabels(array(
			'id'=>'文章ID',
		))->check()){
			$id = $this->form()->getData('id');
			$fields = $this->form()->getData('fields');
			$cat = $this->form()->getData('cat');
			
			if($fields){
				//过滤字段，移除那些不允许的字段
				$fields = FieldHelper::process($fields, 'post', $this->allowed_fields);
			}else{
				//若未指定$fields，取默认值
				$fields = $this->default_fields;
			}
			
			$post = Post::model()->get($id, $fields, $cat);
			if($post){
				Response::json($post);
			}else{
				throw new HttpException('您访问的页面不存在');
			}
		}else{
			$error = $this->form()->getFirstError();
			Response::notify('error', array(
				'message'=>$error['message'],
				'code'=>$error['code'],
			));
		}
	}
	
	public function listAction(){
		
	}
}