<?php
namespace siwi\modules\user\controllers;

use siwi\library\UserController;
use fay\models\tables\Posts;
use fay\models\tables\PostsFiles;
use fay\models\Post;
use fay\models\tables\Files;
use fay\models\Tag;
use fay\models\Category;
use fay\core\Sql;
use fay\core\HttpException;

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
				$post_id = Posts::model()->insert(array(
					'title'=>$this->input->post('title'),
					'cat_id'=>$this->input->post('cat_id', 'intval'),
					'thumbnail'=>$this->input->post('thumbnail', 'intval', 0),
					'abstract'=>$abstract,
					'content'=>$content,
					'create_time'=>$this->current_time,
					'user_id'=>$this->current_user,
					'publish_time'=>$this->current_time,
					'status'=>Posts::STATUS_PUBLISHED,
				));
	
				Post::model()->setPropValueByAlias('siwi_blog_video', $this->input->post('video'), $post_id);
				Post::model()->setPropValueByAlias('siwi_blog_copyright', $this->input->post('copyright'), $post_id);
				
				if($f = $this->input->post('file', 'intval', 0)){
					$file = Files::model()->find($f, 'client_name,is_image');
					if($file){
						PostsFiles::model()->insert(array(
							'file_id'=>$f,
							'post_id'=>$post_id,
							'desc'=>$file['client_name'],
							'is_image'=>$file['is_image'],
							'sort'=>1,
						));
					}
				}
				
				Tag::model()->set($this->input->post('tags'), $post_id);
	
				Response::output('success', '博文发布成功', array('user/post/edit', array(
					'id'=>$post_id,
				)));
			}else{
				$this->flash->set('参数异常');
			}
		}
		$this->view->cats = Category::model()->getNextLevel('_blog');
		
		$this->view->render();
	}
	
	public function edit(){
		$this->layout->title = '编辑博文';
		
		$id = $this->input->get('id', 'intval');
		if(!$id){
			throw new HttpException('不完整的请求');
		}
		
		$post = Posts::model()->find($id);
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
				Posts::model()->update(array(
					'title'=>$this->input->post('title'),
					'cat_id'=>$this->input->post('cat_id', 'intval'),
					'thumbnail'=>$this->input->post('thumbnail', 'intval', 0),
					'abstract'=>$abstract,
					'content'=>$content,
					'create_time'=>$this->current_time,
					'user_id'=>$this->current_user,
					'publish_time'=>$this->current_time,
					'status'=>Posts::STATUS_PUBLISHED,
				), $id);
				
				Post::model()->setPropValueByAlias('siwi_blog_video', $this->input->post('video'), $id);
				Post::model()->setPropValueByAlias('siwi_blog_copyright', $this->input->post('copyright'), $id);
				
				$f = $this->input->post('file', 'intval', 0);
				if($f){
					$file = PostsFiles::model()->fetchRow('post_id = '.$post['id'], 'file_id');
					if($f != $file['file_id']){
						PostsFiles::model()->delete('post_id = '.$post['id']);
						$file = Files::model()->find($f, 'client_name,is_image');
						if($file){
							PostsFiles::model()->insert(array(
								'file_id'=>$f,
								'post_id'=>$id,
								'desc'=>$file['client_name'],
								'is_image'=>$file['is_image'],
								'sort'=>1,
							));
						}
					}
				}else{
					PostsFiles::model()->delete('post_id = '.$post['id']);
				}
	
				Tag::model()->set($this->input->post('tags'), $post['id']);
				
				$this->flash->set('文章编辑成功', 'success');
				
				$post = Posts::model()->find($id);
			}else{
				$this->flash->set('参数异常');
			}
		}
		
		$this->form()->setData($post);
		
		//parent cat
		$cat = Category::model()->get($post['cat_id'], 'parent');
		$this->form()->setData(array('parent_cat'=>$cat['parent']));
		
		//tags
		$sql = new Sql();
		$tags = $sql->from('posts_tags', 'pt', '')
			->joinLeft('tags', 't', 'pt.tag_id = t.id', 'title')
			->where('pt.post_id = '.$post['id'])
			->fetchAll();
		$tag_titles = array();
		foreach($tags as $t){
			$tag_titles[] = $t['title'];
		}
		$this->form()->setData(array('tags'=>implode(',', $tag_titles)));
		
		//file
		$file = PostsFiles::model()->fetchRow('post_id = '.$post['id'], 'file_id,desc');
		$this->view->file = $file;
		$this->form()->setData(array('file'=>isset($file['file_id']) ? $file['file_id'] : ''));
		
		//copyright
		$this->form()->setData(array('copyright'=>Post::model()->getPropValueByAlias('siwi_blog_copyright', $post['id'])));
		
		//video
		$this->form()->setData(array('video'=>Post::model()->getPropValueByAlias('siwi_blog_video', $post['id'])));
		
		$this->view->cats = Category::model()->getNextLevel('_blog');
		$this->view->render();
	}
}