<?php
namespace fay\widgets\post_list\controllers;

use fay\core\Widget;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\Posts;
use fay\helpers\ArrayHelper;
use fay\models\Category;
use fay\models\User;
use fay\helpers\Date;
use fay\core\HttpException;
use fay\models\post\Meta;

class IndexController extends Widget{
	public function index($config){
		empty($config['page_size']) && $config['page_size'] = 10;
		empty($config['page_key']) && $config['page_key'] = 'page';
		empty($config['uri']) && $config['uri'] = 'post/{$id}';
		empty($config['date_format']) && $config['date_format'] = 'pretty';
		isset($config['fields']) || $config['fields'] = array('cat');
		empty($config['pager']) && $config['pager'] = 'system';
		empty($config['pager_template']) && $config['pager_template'] = '';
		empty($config['empty_text']) && $config['empty_text'] = '无相关记录！';
		isset($config['subclassification']) || $config['subclassification'] = true;
		
		//order
		$orders = array(
			'hand'=>'is_top DESC, sort, publish_time DESC',
			'publish_time'=>'publish_time DESC',
			'views'=>'views DESC, publish_time DESC',
		);
		if(!empty($config['order']) && isset($orders[$config['order']])){
			$order = $orders[$config['order']];
		}else{
			$order = $orders['hand'];
		}
		
		$sql = new Sql();
		$sql->from('posts', 'p', 'id,cat_id,title,publish_time,user_id,is_top,thumbnail,abstract');
		
		//限制分类
		if(!empty($config['cat_id_key']) && $this->input->get($config['cat_id_key'])){
			$cat_id = $this->input->get($config['cat_id_key'], 'intval');
		}else if(!empty($config['cat_alias_key']) && $this->input->get($config['cat_alias_key'])){
			$cat_id = $this->input->get($config['cat_alias_key'], 'trim');
		}else{
			$cat_id = isset($config['cat_id']) ? $config['cat_id'] : 0;
		}
		
		if(!empty($cat_id)){
			$cat = Category::model()->get($cat_id, '*', '_system_post');
			if(!$cat){
				throw new HttpException('您访问的页面不存在');
			}else{
				\F::app()->layout->title = empty($cat['seo_title']) ? $cat['title'] : $cat['seo_title'];
				\F::app()->layout->keywords = empty($cat['seo_keywords']) ? $cat['title'] : $cat['seo_keywords'];
				\F::app()->layout->description = empty($cat['seo_description']) ? $cat['description'] : $cat['seo_description'];
			}
			if($config['subclassification']){
				//包含子分类
				$limit_cat_children = Category::model()->getAllIds($cat['id']);
				$limit_cat_children[] = $cat['id'];//加上父节点
				$sql->where(array('cat_id IN (?)'=>$limit_cat_children));
			}else{
				//不包含子分类
				$sql->where(array('cat_id = ?'=>$cat['id']));
			}
		}
		
		$sql->where(array(
			'p.deleted = 0',
			'p.status = '.Posts::STATUS_PUBLISHED,
			"p.publish_time < {$this->current_time}",
		))->order($order);
		
		$listview = new ListView($sql, array(
			'page_size'=>$config['page_size'],
			'page_key'=>$config['page_key'],
		));
		$listview->empty_text = $config['empty_text'];
		$posts = $listview->getData();
		
		$format_posts = array();
		if($posts){
			if(in_array('cat', $config['fields'])){
				//获取所有相关分类
				$cat_ids = ArrayHelper::column($posts, 'cat_id');
				$cats = Category::model()->mget(array_unique($cat_ids), 'id,title,alias');
			}
			
			if(in_array('meta', $config['fields'])){
				$post_metas = Meta::model()->mget(ArrayHelper::column($posts, 'id'));
			}
			
			if(in_array('user', $config['fields'])){
				//获取所有相关作者
				$user_ids = ArrayHelper::column($posts, 'user_id');
				$users = User::model()->getByIds(array_unique($user_ids), 'users.username,users.nickname,users.id,users.avatar');
			}
			
			foreach($posts as $p){
				$format_post = array(
					'post'=>$p,
				);
				if(in_array('cat', $config['fields'])){
					$format_post['cat'] = $cats[$p['cat_id']];
				}
				if(in_array('user', $config['fields'])){
					$format_post['user'] = $users[$p['user_id']];
				}
				if(in_array('meta', $config['fields'])){
					$format_post['meta'] = $post_metas[$p['id']];
				}
				if($config['date_format'] == 'pretty'){
					$format_post['post']['format_publish_time'] = Date::niceShort($p['publish_time']);
				}else if($config['date_format']){
					$format_post['post']['format_publish_time'] = \date($config['date_format'], $p['publish_time']);
				}else{
					$format_post['post']['format_publish_time'] = '';
				}
				
				$format_post['post']['link'] = $this->view->url(str_replace('{$id}', $format_post['post']['id'], $config['uri']));
				
				$format_posts[] = $format_post;
			}
			$posts = $format_posts;
			
			//template
			if(empty($config['template'])){
				$this->view->render('template', array(
					'posts'=>$posts,
					'config'=>$config,
					'alias'=>$this->alias,
					'listview'=>$listview,
				));
			}else{
				if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['template'])){
					\F::app()->view->renderPartial($config['template'], array(
						'posts'=>$posts,
						'config'=>$config,
						'alias'=>$this->alias,
						'listview'=>$listview,
					));
				}else{
					$alias = $this->alias;
					eval('?>'.$config['template'].'<?php ');
				}
			}
		}else{
			echo $config['empty_text'];
		}
		
		if($config['pager'] == 'system'){
			$listview->showPager();
		}else{
			$pager_data = $listview->getPager();
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['pager_template'])){
				\F::app()->view->renderPartial($config['pager_template'], $pager_data + array(
					'listview'=>$listview,
					'config'=>$config,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				extract($pager_data);
				eval('?>'.$config['pager_template'].'<?php ');
			}
		}
		
	}
}