<?php
namespace siwi\modules\user\controllers;

use fay\core\Response;
use siwi\library\UserController;
use fay\models\tables\PostsTable;
use fay\models\tables\PostsFilesTable;
use fay\services\post\PostService;
use fay\models\tables\FilesTable;
use fay\services\post\Tag;
use fay\services\CategoryService;
use fay\core\Sql;
use fay\core\HttpException;
use fay\services\FlashService;

class PostController extends UserController{
	private $rules = array(
		array(array('title', 'abstract'), 'string', array('max'=>500)),
		array(array('video'), 'url', true),
		array(array('title', 'cat_id'), 'require'),
		array(array('cat_id', 'file', 'thumbnail'), 'int'),
		array(array('cat_id'), 'exist', array('table'=>'categories', 'field'=>'id')),
	);
	
	public function __construct(){
		parent::__construct();
	
		$this->layout->current_directory = 'blog';
	}
	
	public function create(){
		$this->layout->title = '发布博文';
		
		$this->form()->setRules($this->rules);
		if($this->input->post()){
			if($this->form()->check()){
				$abstract = $this->input->post('abstract');
				$content = $this->input->post('content');
				$abstract || $abstract = mb_substr(strip_tags($content), 0, 100);
				$post_id = PostsTable::model()->insert(array(
					'title'=>$this->input->post('title'),
					'cat_id'=>$this->input->post('cat_id', 'intval'),
					'thumbnail'=>$this->input->post('thumbnail', 'intval', 0),
					'abstract'=>$abstract,
					'content'=>$content,
					'create_time'=>$this->current_time,
					'user_id'=>$this->current_user,
					'publish_time'=>$this->current_time,
					'status'=>PostsTable::STATUS_PUBLISHED,
				));
	
				PostService::service()->setPropValueByAlias('siwi_blog_video', $this->input->post('video'), $post_id);
				PostService::service()->setPropValueByAlias('siwi_blog_copyright', $this->input->post('copyright'), $post_id);
				
				if($f = $this->input->post('file', 'intval', 0)){
					$file = FilesTable::model()->find($f, 'client_name,is_image');
					if($file){
						PostsFilesTable::model()->insert(array(
							'file_id'=>$f,
							'post_id'=>$post_id,
							'desc'=>$file['client_name'],
							'is_image'=>$file['is_image'],
							'sort'=>1,
						));
					}
				}
				
				TagService::service()->set($this->input->post('tags'), $post_id);
	
				Response::notify('success', '博文发布成功', array('user/post/edit', array(
					'id'=>$post_id,
				)));
			}else{
				FlashService::set('参数异常');
			}
		}
		$this->view->cats = CategoryService::service()->getNextLevel('_blog');
		
		$this->view->render();
	}
	
	public function edit(){
		$this->layout->title = '编辑博文';
		
		$id = $this->input->get('id', 'intval');
		if(!$id){
			throw new HttpException('不完整的请求');
		}
		
		$post = PostsTable::model()->find($id);
		if(!$post){
			throw new HttpException('文章编号不存在');
		}
		if($post['user_id'] != $this->current_user){
			throw new HttpException('您无权限编辑此文章');
		}
		
		$this->form()->setRules($this->rules);
		if($this->input->post()){
			if($this->form()->check()){
				$abstract = $this->input->post('abstract');
				$content = $this->input->post('content');
				$abstract || $abstract = mb_substr(strip_tags($content), 0, 100);
				PostsTable::model()->update(array(
					'title'=>$this->input->post('title'),
					'cat_id'=>$this->input->post('cat_id', 'intval'),
					'thumbnail'=>$this->input->post('thumbnail', 'intval', 0),
					'abstract'=>$abstract,
					'content'=>$content,
					'create_time'=>$this->current_time,
					'user_id'=>$this->current_user,
					'publish_time'=>$this->current_time,
					'status'=>PostsTable::STATUS_PUBLISHED,
				), $id);
				
				PostService::service()->setPropValueByAlias('siwi_blog_video', $this->input->post('video'), $id);
				PostService::service()->setPropValueByAlias('siwi_blog_copyright', $this->input->post('copyright'), $id);
				
				$f = $this->input->post('file', 'intval', 0);
				if($f){
					$file = PostsFilesTable::model()->fetchRow('post_id = '.$post['id'], 'file_id');
					if($f != $file['file_id']){
						PostsFilesTable::model()->delete('post_id = '.$post['id']);
						$file = FilesTable::model()->find($f, 'client_name,is_image');
						if($file){
							PostsFilesTable::model()->insert(array(
								'file_id'=>$f,
								'post_id'=>$id,
								'desc'=>$file['client_name'],
								'is_image'=>$file['is_image'],
								'sort'=>1,
							));
						}
					}
				}else{
					PostsFilesTable::model()->delete('post_id = '.$post['id']);
				}
	
				TagService::service()->set($this->input->post('tags'), $post['id']);
				
				FlashService::set('文章编辑成功', 'success');
				
				$post = PostsTable::model()->find($id);
			}else{
				FlashService::set('参数异常');
			}
		}
		
		$this->form()->setData($post);
		
		//parent cat
		$cat = CategoryService::service()->get($post['cat_id'], 'parent');
		$this->form()->setData(array('parent_cat'=>$cat['parent']));
		
		//tags
		$sql = new Sql();
		$tags = $sql->from(array('pt'=>'posts_tags'), '')
			->joinLeft(array('t'=>'tags'), 'pt.tag_id = t.id', 'title')
			->where('pt.post_id = '.$post['id'])
			->fetchAll();
		$tag_titles = array();
		foreach($tags as $t){
			$tag_titles[] = $t['title'];
		}
		$this->form()->setData(array('tags'=>implode(',', $tag_titles)));
		
		//file
		$file = PostsFilesTable::model()->fetchRow('post_id = '.$post['id'], 'file_id,desc');
		$this->view->file = $file;
		$this->form()->setData(array('file'=>isset($file['file_id']) ? $file['file_id'] : ''));
		
		//copyright
		$this->form()->setData(array('copyright'=>PostService::service()->getPropValueByAlias('siwi_blog_copyright', $post['id'])));
		
		//video
		$this->form()->setData(array('video'=>PostService::service()->getPropValueByAlias('siwi_blog_video', $post['id'])));
		
		$this->view->cats = CategoryService::service()->getNextLevel('_blog');
		$this->view->render();
	}
}