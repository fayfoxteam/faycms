<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\services\post\PostCommentService;
use fay\core\Response;
use fay\models\tables\PostsTable;
use fay\helpers\FieldHelper;
use fay\core\HttpException;
use fay\services\post\PostService;

/**
 * 文章评论
 */
class PostCommentController extends ApiController{
	/**
	 * 默认返回字段
	 */
	private $default_fields = array(
		'comment'=>array(
			'fields'=>array(
				'id', 'content', 'parent', 'create_time',
			)
		),
		'user'=>array(
			'fields'=>array(
				'id', 'nickname', 'avatar',
			)
		),
		'parent'=>array(
			'fields'=>array(
				'comment'=>array(
					'fields'=>array(
						'id', 'content', 'parent', 'create_time',
					)
				),
				'user'=>array(
					'fields'=>array(
						'id', 'nickname', 'avatar',
					)
				),
			),
		),
	);
	
	/**
	 * 可选字段
	 */
	private $allowed_fields = array(
		'comment'=>array(
			'id', 'content', 'parent', 'create_time',
		),
		'user'=>array(
			'id', 'username', 'nickname', 'avatar', 'roles'=>array(
				'id', 'title',
			),
		),
		'parent'=>array(
			'comment'=>array(
				'id', 'content', 'parent', 'create_time',
			),
			'user'=>array(
				'id', 'username', 'nickname', 'avatar', 'roles'=>array(
					'id', 'title',
				),
			),
		),
	);
	/**
	 * 发表评论
	 * @parameter int $post_id 文章ID
	 * @parameter string $content 评论内容
	 * @parameter int $parent 父评论ID
	 */
	public function create(){
		//登录检查
		$this->checkLogin();
		
		//表单验证
		$this->form()->setRules(array(
			array(array('post_id', 'content'), 'required'),
			array(array('post_id'), 'int', array('min'=>1)),
			array(array('parent'), 'int', array('min'=>0)),
			array(array('parent'), 'exist', array(
				'table'=>'post_comments',
				'field'=>'id',
				'conditions'=>array('deleted = 0')
			)),
		))->setFilters(array(
			'post_id'=>'intval',
			'content'=>'trim',
		))->setLabels(array(
			'post_id'=>'文章ID',
			'content'=>'评论内容',
		))->check();
		
		$post_id = $this->form()->getData('post_id');
		$fields = $this->form()->getData('fields');
		
		if(!PostService::isPostIdExist($post_id)){
			Response::notify('error', array(
				'message'=>'文章ID不存在',
				'code'=>'invalid-parameter:post_id-is-not-exist',
			));
		}
		
		$comment_id = PostCommentService::service()->create(
			$post_id,
			$this->form()->getData('content'),
			$this->form()->getData('parent', 0)
		);
		
		if($fields){
			//过滤字段，移除那些不允许的字段
			$fields = FieldHelper::parse($fields, 'comment', $this->allowed_fields);
			
			$comment = PostCommentService::service()->get($comment_id, $fields);
			
			//格式化一下空数组的问题，保证返回给客户端的数据类型一致
			if(isset($comment['parent']['comment']) && empty($comment['parent']['comment'])){
				$comment['parent']['comment'] = new \stdClass();
			}
			if(isset($comment['parent']['user']) && empty($comment['parent']['user'])){
				$comment['parent']['user'] = new \stdClass();
			}
		}
		
		Response::notify('success', array(
			'message'=>'评论成功',
			'data'=>empty($comment) ? new \stdClass() : $comment,
		));
	}
	
	/**
	 * 删除评论
	 * @parameter int $comment_id 评论ID
	 */
	public function delete(){
		//登录检查
		$this->checkLogin();
		
		//表单验证
		$this->form()->setRules(array(
			array(array('comment_id'), 'required'),
			array(array('comment_id'), 'int', array('min'=>1)),
			array(array('comment_id'), 'exist', array(
				'table'=>'post_comments',
				'field'=>'id',
				'conditions'=>array('deleted = 0')
			)),
		))->setFilters(array(
			'comment_id'=>'intval',
		))->setLabels(array(
			'comment_id'=>'评论ID',
		))->check();
		
		$comment_id = $this->form()->getData('comment_id');
		
		if(PostCommentService::service()->checkPermission($comment_id, 'delete')){
			PostCommentService::service()->delete($comment_id);
			Response::notify('success', '评论删除成功');
		}else{
			Response::notify('error', array(
				'message'=>'您无权操作该评论',
				'code'=>'permission-denied',
			));
		}
	}
	
	/**
	 * 从回收站还原评论
	 * @parameter int $comment_id 评论ID
	 */
	public function undelete(){
		//登录检查
		$this->checkLogin();
		
		//表单验证
		$this->form()->setRules(array(
			array(array('comment_id'), 'required'),
			array(array('comment_id'), 'int', array('min'=>1)),
			array(array('comment_id'), 'exist', array(
				'table'=>'post_comments',
				'field'=>'id',
				'conditions'=>array('deleted != 0')
			)),
		))->setFilters(array(
			'comment_id'=>'intval',
		))->setLabels(array(
			'comment_id'=>'评论ID',
		))->check();
		
		$comment_id = $this->form()->getData('comment_id');
		
		if(PostCommentService::service()->checkPermission($comment_id, 'undelete')){
			PostCommentService::service()->undelete($comment_id);
			Response::notify('success', '评论还原成功');
		}else{
			Response::notify('error', array(
				'message'=>'您无权操作该评论',
				'code'=>'permission-denied',
			));
		}
	}
	
	/**
	 * 编辑评论
	 * @parameter int $comment_id 评论ID
	 * @parameter string $content 评论内容
	 */
	public function edit(){
		//登录检查
		$this->checkLogin();
		
		//表单验证
		$this->form()->setRules(array(
			array(array('comment_id', 'content'), 'required'),
			array(array('comment_id'), 'int', array('min'=>1)),
			array(array('comment_id'), 'exist', array(
				'table'=>'post_comments',
				'field'=>'id',
				'conditions'=>array('deleted = 0')
			)),
		))->setFilters(array(
			'post_id'=>'intval',
			'content'=>'trim',
		))->setLabels(array(
			'post_id'=>'文章ID',
			'content'=>'评论内容',
		))->check();
		
		$comment_id = $this->form()->getData('comment_id');
		
		if(PostCommentService::service()->checkPermission($comment_id, 'edit')){
			PostCommentService::service()->update(
				$comment_id,
				$this->form()->getData('content')
			);
			Response::notify('success', '评论修改成功');
		}else{
			Response::notify('error', array(
				'message'=>'您无权操作该评论',
				'code'=>'permission-denied',
			));
		}
	}
	
	/**
	 * 评论列表（盖楼形式）
	 * @parameter int $post_id 文章ID
	 * @parameter string $fields 制定字段
	 * @parameter int $page 页码
	 * @parameter int $page_size 分页大小
	 */
	public function listAction(){
		//验证必须get方式发起请求
		$this->checkMethod('GET');
		
		//表单验证
		$this->form()->setRules(array(
			array(array('post_id'), 'required'),
			array(array('post_id', 'page', 'page_size'), 'int', array('min'=>1)),
			array(array('post_id'), 'exist', array(
				'table'=>'posts',
				'field'=>'id',
				'conditions'=>PostsTable::getPublishedConditions(),
			)),
			array('fields', 'fields'),
		))->setFilters(array(
			'post_id'=>'intval',
			'page'=>'intval',
			'page_size'=>'intval',
			'fields'=>'trim',
		))->setLabels(array(
			'post_id'=>'文章ID',
			'page'=>'页码',
			'page_size'=>'分页大小',
			'fields'=>'字段',
		))->check();
		
		$fields = $this->form()->getData('fields');
		if($fields){
			//过滤字段，移除那些不允许的字段
			$fields = FieldHelper::parse($fields, 'comment', $this->allowed_fields);
		}else{
			$fields = $this->default_fields;
		}
		
		$result = PostCommentService::service()->getList(
			$this->form()->getData('post_id'),
			$this->form()->getData('page_size', 20),
			$this->form()->getData('page', 1),
			$fields
		);
		//将空数组转为空对象，保证给客户端的类型一致
		foreach($result['comments'] as &$r){
			if(isset($r['parent']['comment']) && !$r['parent']['comment']){
				$r['parent']['comment'] = new \stdClass();
			}
			if(isset($r['parent']['user']) && !$r['parent']['user']){
				$r['parent']['user'] = new \stdClass();
			}
		}
		
		Response::json($result);
	}
	
	/**
	 * 评论列表（树形形式）
	 * 层层递归的形式显示所有回复
	 * @parameter int $post_id 文章ID
	 * @parameter string $fields 制定字段
	 * @parameter int $page 页码
	 * @parameter int $page_size 分页大小
	 */
	public function tree(){
		//验证必须get方式发起请求
		$this->checkMethod('GET');
		
		//表单验证
		$this->form()->setRules(array(
			array(array('post_id'), 'required'),
			array(array('post_id', 'page', 'page_size'), 'int', array('min'=>1)),
			array(array('post_id'), 'exist', array(
				'table'=>'posts',
				'field'=>'id',
				'conditions'=>PostsTable::getPublishedConditions(),
			)),
			array('fields', 'fields'),
		))->setFilters(array(
			'post_id'=>'intval',
			'page'=>'intval',
			'page_size'=>'intval',
			'fields'=>'trim',
		))->setLabels(array(
			'post_id'=>'文章ID',
			'page'=>'页码',
			'page_size'=>'分页大小',
			'fields'=>'字段',
		))->check();
		
		$fields = $this->form()->getData('fields');
		if($fields){
			//过滤字段，移除那些不允许的字段
			$fields = FieldHelper::parse($fields, 'comment', $this->allowed_fields);
		}else{
			$fields = $this->default_fields;
		}
		
		Response::json(PostCommentService::service()->getTree(
			$this->form()->getData('post_id'),
			$this->form()->getData('page_size', 20),
			$this->form()->getData('page', 1),
			$fields
		));
	}
	
	/**
	 * 评论列表（会话形式）
	 * 二级回复模式，即QQ空间，微信朋友圈那种一条留言，下边以列表形式返回所有子留言的模式
	 * @parameter int $post_id 文章ID
	 * @parameter string $fields 制定字段
	 * @parameter int $page 页码
	 * @parameter int $page_size 分页大小
	 */
	public function chat(){
		//验证必须get方式发起请求
		$this->checkMethod('GET');
		
		//表单验证
		$this->form()->setRules(array(
			array(array('post_id'), 'required'),
			array(array('post_id', 'page', 'page_size'), 'int', array('min'=>1)),
			array(array('post_id'), 'exist', array(
				'table'=>'posts',
				'field'=>'id',
				'conditions'=>PostsTable::getPublishedConditions(),
			)),
			array('fields', 'fields'),
		))->setFilters(array(
			'post_id'=>'intval',
			'page'=>'intval',
			'page_size'=>'intval',
			'fields'=>'trim',
		))->setLabels(array(
			'post_id'=>'文章ID',
			'page'=>'页码',
			'page_size'=>'分页大小',
			'fields'=>'字段',
		))->check();
		
		$fields = $this->form()->getData('fields');
		if($fields){
			//过滤字段，移除那些不允许的字段
			$fields = FieldHelper::parse($fields, 'comment', $this->allowed_fields);
		}else{
			$fields = $this->default_fields;
		}
		
		Response::json(PostCommentService::service()->getChats(
			$this->form()->getData('post_id'),
			$this->form()->getData('page_size', 20),
			$this->form()->getData('page', 1),
			$fields
		));
	}
	
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
		))->setLabels(array(
			'id'=>'评论ID',
			'fields'=>'字段',
		))->check();
		
		$id = $this->form()->getData('id');
		$fields = $this->form()->getData('fields');
		
		if($fields){
			//过滤字段，移除那些不允许的字段
			$fields = FieldHelper::parse($fields, 'comment', $this->allowed_fields);
		}else{
			//若未指定$fields，取默认值
			$fields = $this->default_fields;
		}
		
		$comment = PostCommentService::service()->get($id, $fields);
		
		//处理下空数组问题
		if(isset($comment['parent']['comment']) && empty($comment['parent']['comment'])){
			$comment['parent']['comment'] = new \stdClass();
		}
		if(isset($comment['parent']['user']) && empty($comment['parent']['user'])){
			$comment['parent']['user'] = new \stdClass();
		}
		
		if($comment){
			Response::json($comment);
		}else{
			throw new HttpException('评论ID不存在');
		}
	}
}