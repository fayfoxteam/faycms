<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\UsersTable;
use fay\models\tables\UsersNotificationsTable;
use fay\models\tables\ActionlogsTable;
use fay\models\tables\RolesTable;
use fay\services\CategoryService;
use fay\common\ListView;
use fay\core\Response;
use fay\helpers\HtmlHelper;
use fay\services\NotificationService;
use fay\core\Sql;
use fay\services\FlashService;
use fay\models\tables\UserProfileTable;

class NotificationController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'notification';
	}
	
	public function create(){
		$this->layout->subtitle = '发送系统消息';
		if($this->input->post()){
			$operators = UsersTable::model()->fetchCol('id', array(
				'role IN (?)'=>$this->input->post('roles', 'intval'),
			));
			$notification_id = NotificationService::service()->send($operators, $this->input->post('title', 'trim'), $this->input->post('content', 'trim'), $this->current_user, $this->input->get('cat_id', null, 0));
			
			$this->actionlog(ActionlogsTable::TYPE_NOTIFICATION, '发送系统信息', $notification_id);
			FlashService::set('消息发送成功', 'success');
		}
		$this->view->notification_cats = CategoryService::service()->getNextLevel('_system_notification');
		$this->view->roles = RolesTable::model()->fetchAll('deleted = 0');
		$this->view->render();
	}
	
	public function my(){
		$this->layout->subtitle = '我的消息';
		
		$sql = new Sql();
		$sql->from(array('un'=>'users_notifications'), 'notification_id,read')
			->joinLeft(array('n'=>'notifications'), 'un.notification_id = n.id', 'title,content,sender,publish_time')
			->joinLeft(array('u'=>'users'), 'n.sender = u.id', 'username,nickname,realname')
			->joinLeft(array('c'=>'categories'), 'n.cat_id = c.id', 'title AS cat_title')
			->where(array(
				'un.user_id = '.$this->current_user,
				'n.publish_time <= '.$this->current_time,
				'un.deleted = 0',
			))
			->order('n.publish_time DESC')
		;
		
		$this->view->listview = new ListView($sql, array(
			'empty_text'=>'<tr><td colspan="4" align="center">无相关记录！</td></tr>',
		));
		
		$this->view->render();
	}
	
	public function delete(){
		$id = $this->input->get('id', 'intval');
		
		UsersNotificationsTable::model()->update(array(
			'deleted'=>1,
		), array(
			'user_id = '.$this->current_user,
			'notification_id = ?'=>$id,
		));
		$this->actionlog(ActionlogsTable::TYPE_NOTIFICATION, '删除系统信息', $id);
		
		Response::notify('success', array(
			'message'=>'一条消息被移入回收站 - '.HtmlHelper::link('撤销', array('admin/notification/undelete', array(
				'id'=>$id,
			))),
			'id'=>$this->input->get('id', 'intval'),
		));
	}
	
	public function undelete(){
		$id = $this->input->get('id', 'intval');
		
		UsersNotificationsTable::model()->update(array(
			'deleted'=>0,
		), array(
			'user_id = '.$this->current_user,
			'notification_id = ?'=>$id,
		));
		$this->actionlog(ActionlogsTable::TYPE_NOTIFICATION, '还原系统信息', $id);
		
		Response::notify('success', array(
			'message'=>'一条消息被还原',
			'id'=>$id,
		));
	}
	
	public function get(){
		//刷新用户在线信息
		UserProfileTable::model()->update(array(
			'last_time_online'=>$this->current_time,
		), $this->current_user);
		
		//获取未读消息数
		$sql = new Sql();
		$notifications = $sql->from(array('un'=>'users_notifications'), 'notification_id')
			->joinLeft(array('n'=>'notifications'), 'un.notification_id = n.id', 'title,content,publish_time')
			->where(array(
				"un.user_id = {$this->current_user}",
				'un.`read` = 0',
				'un.deleted = 0',
				"n.publish_time <= {$this->current_time}",
			))
			->order('n.publish_time DESC')
			->fetchAll();
		
		Response::notify('success', array(
			'data'=>$notifications,
		));
	}
	
	public function mute(){
		UsersNotificationsTable::model()->update(array(
			'read'=>1,
		), "user_id = {$this->current_user}");
	}
	
	public function cat(){
		$this->layout->subtitle = '消息分类';
		$this->view->cats = CategoryService::service()->getTree('_system_notification');
		$root_node = CategoryService::service()->getByAlias('_system_notification', 'id');
		$this->view->root = $root_node['id'];
	
		if($this->checkPermission('admin/notification/cat-create')){
			$this->layout->sublink = array(
				'uri'=>'#create-cat-dialog',
				'text'=>'添加消息分类',
				'html_options'=>array(
					'class'=>'create-cat-link',
					'data-title'=>'消息分类',
					'data-id'=>$root_node['id'],
				),
			);
		}
	
		$this->view->render();
	}
	
	public function setRead(){
		$id = $this->input->get('id', 'intval');
		$read = $this->input->get('read', 'intval');
		
		UsersNotificationsTable::model()->update(array(
			'read'=>$read,
		), array(
			"user_id = {$this->current_user}",
			'notification_id = ?'=>$id,
		));
		
		Response::notify('success', '一条信息被标记为'.($read ? '已读' : '未读'));
	}
	
	public function batch(){
		$ids = $this->input->post('ids', 'intval');
		$action = $this->input->post('batch_action');
		
		switch($action){
			case 'set-read':
				$affected_rows = UsersNotificationsTable::model()->update(array(
					'read'=>1,
				), array(
					"user_id = {$this->current_user}",
					'notification_id IN (?)'=>$ids,
				));
				Response::notify('success', $affected_rows.'条消息被标记为已读');
			break;
			case 'set-unread':
				$affected_rows = UsersNotificationsTable::model()->update(array(
					'read'=>0,
				), array(
					"user_id = {$this->current_user}",
					'notification_id IN (?)'=>$ids,
				));
				Response::notify('success', $affected_rows.'条消息被标记为未读');
			break;
			case 'delete':
				$affected_rows = UsersNotificationsTable::model()->update(array(
					'deleted'=>1,
				), array(
					"user_id = {$this->current_user}",
					'notification_id IN (?)'=>$ids,
				));
				Response::notify('success', $affected_rows.'条消息被删除');
			break;
		}
	}
}