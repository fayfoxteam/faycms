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

class IndexController extends Widget{
	public function index($config){
		empty($config['page_size']) && $config['page_size'] = 10;
		empty($config['cat_type']) && $config['cat_type'] = 'by_input';
		empty($config['cat_key']) && $config['cat_key'] = 'cat_id';
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
		$sql->from('posts', 'p', 'id,cat_id,title,publish_time,user_id,is_top,thumbnail,abstract,comments,views,likes');
		
		//限制分类
		if($config['cat_type'] == 'by_input' && $this->input->get($config['cat_key'])){
			$limit_cat_id = $this->input->get($config['cat_key'], 'intval');
		}else if($config['cat_type'] == 'fixed_cat'){
			$limit_cat_id = $config['fixed_cat_id'];
		}
		
		if(!empty($limit_cat_id)){
			if($config['subclassification']){
				//包含子分类
				$limit_cat_children = Category::model()->getAllIds($limit_cat_id);
				$limit_cat_children[] = $limit_cat_id;//加上父节点
				$sql->where(array('cat_id IN (?)'=>$limit_cat_children));
			}else{
				//不包含子分类
				$sql->where(array('cat_id = ?'=>$limit_cat_id));
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
		
		if($posts){
			if(in_array('cat', $config['fields'])){
				//获取所有相关分类
				$cat_ids = ArrayHelper::column($posts, 'cat_id');
				$cats = Category::model()->getByIDs(array_unique($cat_ids), 'id,title,alias');
			}
			
			if(in_array('user', $config['fields'])){
				//获取所有相关作者
				$user_ids = ArrayHelper::column($posts, 'user_id');
				$users = User::model()->getByIds(array_unique($user_ids), 'users.username,users.nickname,users.id,users.avatar');
			}
			
			foreach($posts as &$p){
				if(in_array('cat', $config['fields'])){
					$p['cat'] = $cats[$p['cat_id']];
				}
				if(in_array('user', $config['fields'])){
					$p['user'] = $users[$p['user_id']];
				}
				if($config['date_format'] == 'pretty'){
					$p['publish_format_time'] = Date::niceShort($p['publish_time']);
				}else if($config['date_format']){
					$p['publish_format_time'] = \date($config['date_format'], $p['publish_time']);
				}else{
					$p['publish_format_time'] = '';
				}
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
				if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $config['template'])){
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
			if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $config['pager_template'])){
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