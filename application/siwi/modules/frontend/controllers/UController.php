<?php
namespace siwi\modules\frontend\controllers;

use siwi\library\FrontController;
use fay\models\tables\UsersTable;
use fay\core\Sql;
use fay\services\CategoryService;
use fay\models\tables\PostsTable;
use fay\models\tables\MessagesTable;
use fay\common\ListView;
use fay\models\tables\FollowersTable;
use fay\services\UserService;

class UController extends FrontController{
	/**
	 * 当前显示的用户，非当前登录用户
	 */
	public $user_id;
	
	public function __construct(){
		parent::__construct();
		$this->layout_template = 'home';
		
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
		
		$this->layout->current_directory = 'home';
		
		$this->user_id = $this->input->get('id', 'intval');
		$this->layout->user = UsersTable::model()->find($this->user_id, 'avatar,nickname');
		
		if(FollowersTable::model()->fetchRow(array(
			'follower = '.$this->current_user,
			'user_id'=>$this->user_id,
		))){
			$this->layout->is_follow = true;
		}else{
			$this->layout->is_follow = false;
		}

		$this->layout->popularity = intval(UserService::service()->getPropValueByAlias('popularity', $this->user_id));
		$this->layout->creativity = intval(UserService::service()->getPropValueByAlias('creativity', $this->user_id));
		$this->layout->fans = intval(UserService::service()->getPropValueByAlias('fans', $this->user_id));
		$this->layout->follow = intval(UserService::service()->getPropValueByAlias('follow', $this->user_id));
	}
	
	public function index(){
		
		$sql = new Sql();
		
		//素材
		$cat_work = CategoryService::service()->getByAlias('_material', 'left_value,right_value');
		$this->view->works = $sql->from(array('p'=>'posts'), 'id,title,abstract,publish_time,thumbnail,comments,user_id,cat_id')
			->joinLeft(array('u'=>'users'), 'p.user_id = u.id', 'nickname')
			->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'title AS cat_title, parent AS parent_cat_id')
			->joinLeft(array('pc'=>'categories'), 'c.parent = pc.id', 'title AS parent_cat_title')
			->order('is_top DESC, p.sort, publish_time DESC')
			->where(array(
				'p.user_id = ?'=>$this->user_id,
				'c.left_value > '.$cat_work['left_value'],
				'c.right_value < '.$cat_work['right_value'],
				'p.deleted = 0',
				'p.status = '.PostsTable::STATUS_PUBLISHED,
				'p.publish_time < '.$this->current_time,
			))
			->fetchAll()
		;
		
		//博文
		$cat_blog = CategoryService::service()->getByAlias('_blog', 'left_value,right_value');
		$this->view->posts = $sql->from(array('p'=>'posts'), 'id,title,abstract,publish_time,thumbnail,comments,user_id')
			->joinLeft(array('u'=>'users'), 'p.user_id = u.id', 'nickname')
			->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id')
			->order('is_top DESC, p.sort, publish_time DESC')
			->where(array(
				'p.user_id = ?'=>$this->user_id,
				'c.left_value > '.$cat_blog['left_value'],
				'c.right_value < '.$cat_blog['right_value'],
				'p.deleted = 0',
				'p.status = '.PostsTable::STATUS_PUBLISHED,
				'p.publish_time < '.$this->current_time,
			))
			->fetchAll()
		;
		
		//留言
		$sql->from(array('m'=>'messages'))
			->joinLeft(array('u'=>'users'), 'm.user_id = u.id', 'nickname,avatar')
			->where(array(
				'm.target = '.$this->user_id,
				'm.parent = 0',
				'm.type = '.MessagesTable::TYPE_USER_MESSAGE,
				'm.status = '.MessagesTable::STATUS_APPROVED,
				'm.deleted = 0',
			))
			->order('id DESC');
		$this->view->listview = new ListView($sql, array(
			'item_view'=>'_message_list_item',
		));
		$this->view->render();
	}
	
}