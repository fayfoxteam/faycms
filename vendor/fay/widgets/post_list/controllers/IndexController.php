<?php
namespace fay\widgets\post_list\controllers;

use fay\core\Widget;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\Posts;
use fay\helpers\ArrayHelper;
use fay\models\Category;
use fay\models\User;

class IndexController extends Widget{
	public function index($config){
		empty($config['page_size']) && $config['page_size'] = 10;
		empty($config['cat_key']) && $config['cat_key'] = 'cat_id';
		empty($config['page_key']) && $config['page_key'] = 'page';
		empty($config['uri']) && $config['uri'] = 'post/{$id}';
		
		$sql = new Sql();
		$sql->from('posts', 'p', 'id,cat_id,title,publish_time,user_id,is_top,thumbnail,abstract,comments,views,likes');
		
		
		$sql->where(array(
			'p.deleted = 0',
			'p.status = '.Posts::STATUS_PUBLISHED,
			"p.publish_time < {$this->current_time}",
		));
		
		$listview = new ListView($sql, array(
			'page_size'=>$config['page_size'],
			'page_key'=>$config['page_key'],
		));
		
		$posts = $listview->getData();
		
		if($posts){
			//获取所有相关分类
			$cat_ids = ArrayHelper::column($posts, 'cat_id');
			$cats = Category::model()->get(array_unique($cat_ids), 'id,title,alias');
			
			//获取所有相关作者
			$user_ids = ArrayHelper::column($posts, 'user_id');
			$users = User::model()->getByIds(array_unique($user_ids));
		}
		
		foreach($posts as &$p){
			$p['cat'] = $cats[$p['cat_id']];
			$p['user'] = $users[$p['user_id']];
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