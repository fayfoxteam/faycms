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
		empty($config['cat_key']) && $config['cat_key'] = 'cat_id';
		empty($config['page_key']) && $config['page_key'] = 'page';
		empty($config['uri']) && $config['uri'] = 'post/{$id}';
		empty($config['date_format']) && $config['date_format'] = 'pretty';
		empty($config['fields']) && $config['fields'] = array();
		
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
		
		$sql->where(array(
			'p.deleted = 0',
			'p.status = '.Posts::STATUS_PUBLISHED,
			"p.publish_time < {$this->current_time}",
		))->order($order);
		
		$listview = new ListView($sql, array(
			'page_size'=>$config['page_size'],
			'page_key'=>$config['page_key'],
		));
		
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
		}
		
		//@todo 分页条还没做，选择性搜索某些列还没做
		//template
		if(empty($config['template'])){
			$this->view->render('template', array(
				'posts'=>$posts,
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
					'posts'=>$posts,
					'config'=>$config,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				eval('?>'.$config['template'].'<?php ');
			}
		}
		
	}
}