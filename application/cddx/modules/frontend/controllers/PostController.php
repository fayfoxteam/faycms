<?php
namespace cddx\modules\frontend\controllers;

use cddx\library\FrontController;
use fay\core\Sql;
use fay\models\tables\Posts;
use fay\core\HttpException;
use fay\services\Category;
use fay\common\ListView;
use fay\core\db\Expr;
use fay\models\Post;

class PostController extends FrontController{
	public function index(){
		$cat_id = $this->input->get('cat_id', 'intval');
		
		//获取分类
		if(!$cat_id || !$cat = Category::service()->get($cat_id)){
			throw new HttpException('您请求的页面不存在');
		}
		
		$sql = new Sql();
		$sql->from(array('p'=>'posts'))
			->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'title AS cat_title')
			->where(array(
				'p.deleted = 0',
				'p.status = '.Posts::STATUS_PUBLISHED,
				"p.publish_time < {$this->current_time}",
				'c.left_value >= '.$cat['left_value'],
				'c.right_value <= '.$cat['right_value'],
			))
			->order('p.is_top DESC, p.sort, p.publish_time DESC')
		;
			
		$this->view->listview = new ListView($sql, array(
			'page_size'=>15,
			'reload'=>$this->view->url('cat-'.$cat['id']),
		));
		
		if($cat['right_value'] - $cat['left_value'] == 1){
			//叶子节点
			$parent_cat = Category::service()->get($cat['parent']);
			$child_cats = Category::service()->getTreeByParentId($cat['parent']);
			$left_cats = $parent_cat;
			$left_cats['children'] = $child_cats;
		}else{
			//父节点
			$child_cats = Category::service()->getTreeByParentId($cat['id']);
			$left_cats = $cat;
			$left_cats['children'] = $child_cats;
		}
		
		$this->view->left_cats = $left_cats;
		$this->view->cat = $cat;
		
		$this->layout->title = $cat['title'];
		$this->layout->keywords = $cat['seo_keywords'] ? $cat['seo_keywords'] : $cat['title'];
		$this->layout->description = $cat['seo_description'] ? $cat['seo_description'] : $cat['description'];
		
		$this->view->render();
	}
	
	public function item(){
		$id = $this->input->get('id', 'intval');
		
		if(!$id || !$post = Post::model()->get($id, 'files.file_id,files.description,user.id,user.username,user.nickname')){
			throw new HttpException('页面不存在');
		}
		Posts::model()->update(array(
			'last_view_time'=>$this->current_time,
			'views'=>new Expr('views + 1'),
		), $id);
		
		$this->layout->title = $post['post']['seo_title'];
		$this->layout->keywords = $post['post']['seo_keywords'];
		$this->layout->description = $post['post']['seo_description'];
		
		$cat = Category::service()->get($post['post']['cat_id']);
		if($cat['right_value'] - $cat['left_value'] == 1){
			//叶子节点
			$parent_cat = Category::service()->get($cat['parent']);
			$child_cats = Category::service()->getTreeByParentId($cat['parent']);
			$left_cats = $parent_cat;
			$left_cats['children'] = $child_cats;
		}else{
			//父节点
			$child_cats = Category::service()->getTreeByParentId($cat['id']);
			$left_cats = $cat;
			$left_cats['children'] = $child_cats;
		}
		
		$this->view->assign(array(
			'left_cats'=>$left_cats,
			'post'=>$post,
		))->render();
	}
}