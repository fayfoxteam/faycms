<?php
namespace fay\widgets\tag_post_list\controllers;

use fay\helpers\ArrayHelper;
use fay\models\tables\Tags;
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
	
	/**
	 * 排序方式
	 */
	private $order_map = array(
		'hand'=>'is_top DESC, sort, publish_time DESC',
		'publish_time'=>'publish_time DESC',
		'views'=>'views DESC, publish_time DESC',
	);
	
	/**
	 * 配置信息
	 */
	private $config;
	
	public function index($config){
		$this->initConfig($config);
		
		$listview = $this->getListView();
		$posts = $listview->getData();
		
		if($posts){
			$fields = $this->getFields();
			
			$posts = Post::service()->mget(ArrayHelper::column($posts, 'post_id'), $fields, false);
			
			$posts = $this->formatPosts($posts);
			
			//template
			if(empty($this->config['template'])){
				$this->view->render('template', array(
					'posts'=>$posts,
					'config'=>$this->config,
					'alias'=>$this->alias,
					'listview'=>$listview,
				));
			}else{
				if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $this->config['template'])){
					\F::app()->view->renderPartial($this->config['template'], array(
						'posts'=>$posts,
						'config'=>$this->config,
						'alias'=>$this->alias,
						'listview'=>$listview,
					));
				}else{
					$alias = $this->alias;
					eval('?>'.$this->config['template'].'<?php ');
				}
			}
		}else{
			echo $this->config['empty_text'];
		}
		
		if($this->config['pager'] == 'system'){
			$listview->showPager();
		}else{
			$pager_data = $listview->getPager();
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $this->config['pager_template'])){
				\F::app()->view->renderPartial($this->config['pager_template'], $pager_data + array(
					'listview'=>$listview,
					'config'=>$this->config,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				extract($pager_data);
				eval('?>'.$this->config['pager_template'].'<?php ');
			}
		}
	}
	
	/**
	 * 初始化配置
	 * @param array $config
	 * @return array
	 */
	private function initConfig($config){
		empty($config['tag_title_key']) && $config['tag_title_key'] = 'tag_title';
		empty($config['tag_id_key']) && $config['tag_id_key'] = 'tag_id';
		empty($config['page_size']) && $config['page_size'] = 10;
		empty($config['page_key']) && $config['page_key'] = 'page';
		empty($config['uri']) && $config['uri'] = 'post/{$id}';
		empty($config['date_format']) && $config['date_format'] = 'pretty';
		isset($config['fields']) || $config['fields'] = array('category');
		empty($config['pager']) && $config['pager'] = 'system';
		empty($config['pager_template']) && $config['pager_template'] = '';
		empty($config['empty_text']) && $config['empty_text'] = '无相关记录！';
		empty($config['cat_id']) && $config['cat_id'] = 0;
		
		return $this->config = $config;
	}
	
	/**
	 * 获取排序方式
	 * @return string
	 */
	private function getOrder(){
		if(!empty($this->config['order']) && isset($this->order_map[$this->config['order']])){
			return $this->order_map[$this->config['order']];
		}else{
			return $this->order_map['hand'];
		}
	}
	
	/**
	 * 获取$fields
	 * @return array
	 */
	private function getFields(){
		$fields = array(
			'post'=>$this->fields['post']
		);
		
		//文章缩略图
		if(!empty($this->config['post_thumbnail_width']) || !empty($this->config['post_thumbnail_height'])){
			$fields['post']['extra'] = array(
				'thumbnail'=>(empty($this->config['post_thumbnail_width']) ? 0 : $this->config['post_thumbnail_width']) .
					'x' .
					(empty($this->config['post_thumbnail_height']) ? 0 : $this->config['post_thumbnail_height']),
			);
		}
		//分类信息
		if(in_array('category', $this->config['fields'])){
			$fields['category'] = $this->fields['category'];
		}
		//计数器
		if(in_array('meta', $this->config['fields'])){
			$fields['meta'] = $this->fields['meta'];
		}
		//用户信息
		if(in_array('user', $this->config['fields'])){
			$fields['user'] = $this->fields['user'];
		}
		//附件缩略图
		if(in_array('files', $this->config['fields'])){
			$file_fields = $this->fields['files'];
			if(!empty($this->config['file_thumbnail_width']) || !empty($this->config['file_thumbnail_height'])){
				$file_fields['extra'] = array(
					'thumbnail'=>(empty($this->config['file_thumbnail_width']) ? 0 : $this->config['file_thumbnail_width']) .
						'x' .
						(empty($this->config['file_thumbnail_height']) ? 0 : $this->config['file_thumbnail_height']),
				);
			}
			$fields['files'] = $file_fields;
		}
		
		return $fields;
	}
	
	/**
	 * 获取ListView对象
	 * @return ListView
	 * @throws HttpException
	 * @throws \fay\core\ErrorException
	 */
	private function getListView(){
		//获取标签
		if(!empty($this->config['tag_id_key']) && $this->input->get($this->config['tag_id_key'])){
			$tag_id = $this->input->get($this->config['tag_id_key'], 'intval');
		}else if(!empty($this->config['tag_title_key']) && $this->input->get($this->config['tag_title_key'])){
			$tag = Tags::model()->fetchRow(array(
				'title = ?'=>$this->input->get($this->config['tag_title_key'])
			), 'id');
			if(!$tag){
				throw new HttpException('您访问的页面不存在');
			}
			$tag_id = $tag['id'];
		}else{
			$tag_id = isset($this->config['tag_id']) ? $this->config['tag_id'] : 0;
		}
		
		if(empty($tag_id)){
			throw new HttpException('您访问的页面不存在');
		}
		
		$sql = new Sql();
		$sql->from(array('pt'=>'posts_tags'), 'post_id')
			->joinLeft(array('p'=>'posts'), 'pt.post_id = p.id')
			->where('tag_id = ?', $tag_id)
			->where(Posts::getPublishedConditions('p'))
			->order($this->getOrder())
		;
		if($this->config['cat_id']){
			$cat_ids = Category::service()->getChildIds($this->config['cat_id']);
			$cat_ids[] = $this->config['cat_id'];
			$sql->where('p.cat_id IN (?)', $cat_ids);
		}
		
		$listview = new ListView($sql, array(
			'page_size'=>$this->config['page_size'],
			'page_key'=>$this->config['page_key'],
		));
		$listview->empty_text = $this->config['empty_text'];
		
		return $listview;
	}
	
	/**
	 * @param array $posts
	 * @return array
	 */
	private function formatPosts($posts){
		foreach($posts as &$p){
			//附加格式化日期
			if($this->config['date_format'] == 'pretty'){
				$p['post']['format_publish_time'] = Date::niceShort($p['post']['publish_time']);
			}else if($this->config['date_format']){
				$p['post']['format_publish_time'] = \date($this->config['date_format'], $p['post']['publish_time']);
			}else{
				$p['post']['format_publish_time'] = '';
			}
			
			//附加文章链接
			$p['post']['link'] = $this->view->url(str_replace(
				array(
					'{$id}', '{$cat_id}'
				),
				array(
					$p['post']['id'], $p['post']['cat_id']
				),
				$this->config['uri']
			));
		}
		
		return $posts;
	}
}