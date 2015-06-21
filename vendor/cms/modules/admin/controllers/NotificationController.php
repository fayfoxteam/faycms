<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Users;
use fay\models\tables\UsersNotifications;
use fay\models\tables\Actionlogs;
use fay\models\tables\Roles;
use fay\models\Category;
use fay\common\ListView;
use fay\core\Response;
use fay\helpers\Html;
use fay\models\Notification;
use fay\core\Sql;
use fay\models\Flash;

class NotificationController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'notification';
	}
	
	public function create(){
		$this->layout->subtitle = '发送系统消息';
		if($this->input->post()){
			$operators = Users::model()->fetchCol('id', array(
				'role IN (?)'=>$this->input->post('roles', 'intval'),
			));
			$notification_id = Notification::model()->send($operators, $this->input->post('title', 'trim'), $this->input->post('content', 'trim'), $this->current_user, $this->input->get('cat_id', null, 0));
			
			$this->actionlog(Actionlogs::TYPE_NOTIFICATION, '发送系统信息', $notification_id);
			Flash::set('消息发送成功', 'success');
		}
		$this->view->notification_cats = Category::model()->getNextLevel('_system_notification');
		$this->view->roles = Roles::model()->fetchAll('deleted = 0');
		$this->view->render();
	}
	
	public function my(){
		$this->layout->subtitle = '我的消息';
		
		$sql = new Sql();
		$sql->from('users_notifications', 'un', 'notification_id,read')
			->joinLeft('notifications', 'n', 'un.notification_id = n.id', 'title,content,sender,publish_time')
			->joinLeft('users', 'u', 'n.sender = u.id', 'username,nickname,realname')
			->joinLeft('categories', 'c', 'n.cat_id = c.id', 'title AS cat_title')
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
		
		UsersNotifications::model()->update(array(
			'deleted'=>1,
		), array(
			'user_id = '.$this->current_user,
			'notification_id = ?'=>$id,
		));
		$this->actionlog(Actionlogs::TYPE_NOTIFICATION, '删除系统信息', $id);
		
		Response::output('success', array(
			'message'=>'一条消息被移入回收站 - '.Html::link('撤销', array('admin/notification/undelete', array(
				'id'=>$id,
			))),
			'id'=>$this->input->get('id', 'intval'),
		));
	}
	
	public function undelete(){
		$id = $this->input->get('id', 'intval');
		
		UsersNotifications::model()->update(array(
			'deleted'=>0,
		), array(
			'user_id = '.$this->current_user,
			'notification_id = ?'=>$id,
		));
		$this->actionlog(Actionlogs::TYPE_NOTIFICATION, '还原系统信息', $id);
		
		Response::output('success', array(
			'message'=>'一条消息被还原',
			'id'=>$id,
		));
	}
	
	public function get(){
		//刷新用户在线信息
		Users::model()->update(array(
			'last_time_online'=>$this->current_time,
		), $this->current_user);
		
		//获取未读消息数
		$sql = new Sql();
		$notifications = $sql->from('users_notifications', 'un', 'notification_id')
			->joinLeft('notifications', 'n', 'un.notification_id = n.id', 'title,content,publish_time')
			->where(array(
				"un.user_id = {$this->current_user}",
				'un.`read` = 0',
				'un.deleted = 0',
				"n.publish_time <= {$this->current_time}",
			))
			->order('n.publish_time DESC')
			->fetchAll();
		
		Response::output('success', array(
			'data'=>$notifications,
		));
	}
	
	public function mute(){
		UsersNotifications::model()->update(array(
			'read'=>1,
		), "user_id = {$this->current_user}");
	}
	
	public function cat(){
		$this->layout->subtitle = '消息分类';
		$this->view->cats = Category::model()->getTree('_system_notification');
		$root_node = Category::model()->getByAlias('_system_notification', 'id');
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
		
		UsersNotifications::model()->update(array(
			'read'=>$read,
		), array(
			"user_id = {$this->current_user}",
			'notification_id = ?'=>$id,
		));
		
		Response::output('success', '一条信息被标记为'.($read ? '已读' : '未读'));
	}
	
	public function batch(){
		$ids = $this->input->post('ids', 'intval');
		$action = $this->input->post('batch_action');
		if(empty($action)){
			$action = $this->input->post('batch_action_2');
		}
		switch($action){
			case 'set-read':
				$affected_rows = UsersNotifications::model()->update(array(
					'read'=>1,
				), array(
					"user_id = {$this->current_user}",
					'notification_id IN (?)'=>$ids,
				));
				Response::output('success', $affected_rows.'条消息被标记为已读');
			break;
			case 'set-unread':
				$affected_rows = UsersNotifications::model()->update(array(
					'read'=>0,
				), array(
					"user_id = {$this->current_user}",
					'notification_id IN (?)'=>$ids,
				));
				Response::output('success', $affected_rows.'条消息被标记为未读');
			break;
			case 'delete':
				$affected_rows = UsersNotifications::model()->update(array(
					'deleted'=>1,
				), array(
					"user_id = {$this->current_user}",
					'notification_id IN (?)'=>$ids,
				));
				Response::output('success', $affected_rows.'条消息被删除');
			break;
		}
	}
}