<?php
namespace fay\widgets\tags\controllers;

use fay\services\Tag;
use fay\widget\Widget;
use fay\services\Category;

class IndexController extends Widget{
	private $fields = array(
		'tag'=>array(
			'fields'=>array(
				'id', 'title'
			)
		),
		'counter'=>array(
			'fields'=>array(
				'posts'
			)
		)
	);
	
	/**
	 * 排序方式
	 */
	private $order_map = array(
		'sort'=>'t.sort, t.id DESC',
		'posts'=>'tc.posts',
		'create_time'=>'id DESC',
	);
	
	public function getData(){
		$this->initConfig($config);
		
		$tags = Tag::service()->getLimit(
			$this->fields,
			$this->config['number'],
			$this->getOrder()
		);
		
		//格式化分类链接
		$tags = $this->setLink($tags, $this->config['uri']);
		
		return $tags;
	}
	
	public function index(){
		$this->initConfig($config);
		
		$tags = Tag::service()->getLimit(
			$this->fields,
			$this->config['number'],
			$this->getOrder()
		);
		
		//格式化分类链接
		$tags = $this->setLink($tags, $this->config['uri']);
		
		//若无分类可显示，则不显示该widget
		if(empty($tags)){
			return;
		}
		
		if(empty($this->config['template'])){
			$this->view->render('template', array(
				'tags'=>$tags,
				'config'=>$this->config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $this->config['template'])){
				\F::app()->view->renderPartial($this->config['template'], array(
					'tags'=>$tags,
					'config'=>$this->config,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				eval('?>'.$this->config['template'].'<?php ');
			}
		}
	}
	
	/**
	 * 为分类列表添加link字段
	 * @param array $tags
	 * @param string $uri
	 * @return array
	 */
	private function setLink($tags, $uri){
		foreach($tags as &$t){
			$t['tag']['link'] = $this->view->url(str_replace(array(
				'{$id}', '{$title}',
			), array(
				$t['tag']['id'], urlencode($t['tag']['title']),
			), $uri));
		}
		
		return $tags;
	}
	
	/**
	 * 初始化配置
	 * @param array $config
	 * @return array
	 */
	protected function initConfig($config){
		empty($config['number']) && $config['number'] = 10;
		
		//排序方式
		if(!isset($config['sort']) || !in_array($config['sort'], array_keys($this->order_map))){
			$config['sort'] = 'sort';
		}
		
		//uri
		if(empty($config['uri'])){
			$config['uri'] = 'tag/{$title}';
		}
		
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
			return $this->order_map['sort'];
		}
	}
}