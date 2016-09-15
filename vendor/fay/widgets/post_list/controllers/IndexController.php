<?php
namespace fay\widgets\post_list\controllers;

use fay\helpers\ArrayHelper;
use fay\services\Post;
use fay\widget\Widget;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\Posts;
use fay\services\Category;
use fay\services\User;
use fay\helpers\Date;
use fay\core\HttpException;

class IndexController extends Widget{
	/**
	 * 返回字段
	 */
	private $fields = array(
		'post'=>array(
			'fields'=>array(
				'id', 'cat_id', 'title', 'publish_time', 'user_id', 'is_top', 'thumbnail', 'abstract'
			)
		),
		'user'=>array(
			'fields'=>array(
				'id', 'username', 'nickname', 'avatar'
			)
		),
		'meta'=>array(
			'fields'=>array(
				'comments', 'views', 'likes'
			)
		),
		'files'=>array(
			'fields'=>array(
				'id', 'description', 'url', 'thumbnail', 'is_image'
			)
		),
		'category'=>array(
			'fields'=>array(
				'id', 'title', 'alias'
			)
		),
		'tags'=>array(
			'fields'=>array(
				'id', 'title',
			)
		),
	);
	
	private $order_map = array(
		'hand'=>'is_top DESC, sort, publish_time DESC',
		'publish_time'=>'publish_time DESC',
		'views'=>'views DESC, publish_time DESC',
	);
	
	public function index($config){
		$config = $this->initConfig($config);
		
		$listview = $this->getListView($config);
		$posts = $listview->getData();
		
		if($posts){
			$fields = array(
				'post'=>$this->fields['post']
			);
			if(!empty($config['post_thumbnail_width']) || !empty($config['post_thumbnail_height'])){
				$fields['post']['extra'] = array(
					'thumbnail'=>(empty($config['post_thumbnail_width']) ? 0 : $config['post_thumbnail_width']) .
						'x' .
						(empty($config['post_thumbnail_height']) ? 0 : $config['post_thumbnail_height']),
				);
			}
			
			if(in_array('category', $config['fields'])){
				$fields['category'] = $this->fields['category'];
			}
			if(in_array('meta', $config['fields'])){
				$fields['meta'] = $this->fields['meta'];
			}
			if(in_array('user', $config['fields'])){
				$fields['user'] = $this->fields['user'];
			}
			if(in_array('files', $config['fields'])){
				$file_fields = $this->fields['files'];
				if(!empty($config['file_thumbnail_width']) || !empty($config['file_thumbnail_height'])){
					$file_fields['extra'] = array(
						'thumbnail'=>(empty($config['file_thumbnail_width']) ? 0 : $config['file_thumbnail_width']) .
							'x' .
							(empty($config['file_thumbnail_height']) ? 0 : $config['file_thumbnail_height']),
					);
				}
				$fields['files'] = $file_fields;
			}
			
			$posts = Post::service()->mget(ArrayHelper::column($posts, 'id'), $fields, false);
			foreach($posts as &$p){
				//附加格式化日期
				if($config['date_format'] == 'pretty'){
					$p['post']['format_publish_time'] = Date::niceShort($p['post']['publish_time']);
				}else if($config['date_format']){
					$p['post']['format_publish_time'] = \date($config['date_format'], $p['post']['publish_time']);
				}else{
					$p['post']['format_publish_time'] = '';
				}
				
				//附加文章链接
				$p['post']['link'] = $this->view->url(str_replace('{$id}', $p['post']['id'], $config['uri']));
			}
			
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
	
	private function initConfig($config){
		empty($config['page_size']) && $config['page_size'] = 10;
		empty($config['page_key']) && $config['page_key'] = 'page';
		empty($config['uri']) && $config['uri'] = 'post/{$id}';
		empty($config['date_format']) && $config['date_format'] = 'pretty';
		isset($config['fields']) || $config['fields'] = array('cat');
		empty($config['pager']) && $config['pager'] = 'system';
		empty($config['pager_template']) && $config['pager_template'] = '';
		empty($config['empty_text']) && $config['empty_text'] = '无相关记录！';
		isset($config['subclassification']) || $config['subclassification'] = true;
		
		return $config;
	}
	
	/**
	 * 获取排序方式
	 * @param array $config
	 * @return string
	 */
	private function getOrder($config){
		if(!empty($config['order']) && isset($this->order_map[$config['order']])){
			return $this->order_map[$config['order']];
		}else{
			return $this->order_map['hand'];
		}
	}
	
	/**
	 * 获取ListView对象
	 * @param array $config
	 * @return ListView
	 * @throws HttpException
	 * @throws \fay\core\ErrorException
	 */
	private function getListView($config){
		$sql = new Sql();
		$sql->from(array('p'=>'posts'), 'id');
		
		//限制分类
		if(!empty($config['cat_id_key']) && $this->input->get($config['cat_id_key'])){
			$cat_id = $this->input->get($config['cat_id_key'], 'intval');
		}else if(!empty($config['cat_alias_key']) && $this->input->get($config['cat_alias_key'])){
			$cat_id = $this->input->get($config['cat_alias_key'], 'trim');
		}else{
			$cat_id = isset($config['cat_id']) ? $config['cat_id'] : 0;
		}
		
		if(!empty($cat_id)){
			$cat = Category::service()->get($cat_id, '*', '_system_post');
			if(!$cat){
				throw new HttpException('您访问的页面不存在');
			}else if($cat['alias'] != '_system_post'){
				\F::app()->layout->assign(array(
					'title'=>empty($cat['seo_title']) ? $cat['title'] : $cat['seo_title'],
					'keywords'=>empty($cat['seo_keywords']) ? $cat['title'] : $cat['seo_keywords'],
					'description'=>empty($cat['seo_description']) ? $cat['description'] : $cat['seo_description'],
				));
			}
			if($config['subclassification']){
				//包含子分类
				$limit_cat_children = Category::service()->getChildIds($cat['id']);
				$limit_cat_children[] = $cat['id'];//加上父节点
				$sql->where(array('cat_id IN (?)'=>$limit_cat_children));
			}else{
				//不包含子分类
				$sql->where(array('cat_id = ?'=>$cat['id']));
			}
		}
		
		$sql->where(Posts::getPublishedConditions('p'))
			->order($this->getOrder($config));
		
		$listview = new ListView($sql, array(
			'page_size'=>$config['page_size'],
			'page_key'=>$config['page_key'],
		));
		$listview->empty_text = $config['empty_text'];
		
		return $listview;
	}
}