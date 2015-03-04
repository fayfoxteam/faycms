<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\models\tables\Messages;
use fay\common\ListView;
use fay\models\Setting;

class ChatController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'message';
	}
	
	public function index(){
		$this->layout->subtitle = '会话';
		
		$this->layout->_setting_panel = '_setting_index';
		$_setting_key = 'admin_chat_index';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array(
			'display_name'=>'username',
			'page_size'=>20,
		);
		$this->form('setting')->setModel(Setting::model())
			->setJsModel('setting')
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key,
			));
		
		$sql = new Sql();
		$sql->from('messages', 'm')
			->joinLeft('users', 'u', 'm.user_id = u.id', 'realname,username,nickname,avatar')
			->joinLeft('users', 'u2', 'm.target = u2.id', 'username AS target_username,nickname AS target_nickname,realname AS target_realname')
			->where(array(
				'm.type = '.Messages::TYPE_USER_MESSAGE,
				'm.root = 0',
			))
			->order('id DESC')
		;
		
		if($this->input->get('deleted')){
			$sql->where(array(
				'm.deleted = 1',
			));
		}else if($this->input->get('status') !== null && $this->input->get('status') !== ''){
			$sql->where(array(
				'm.status = ?'=>$this->input->get('status', 'intval'),
				'm.deleted = 0',
			));
		}else{
			$sql->where('m.deleted = 0');
		}
		
		$listview = new ListView($sql, array(
			'pageSize'=>!empty($this->view->_settings['page_size']) ? $this->view->_settings['page_size'] : 20,
		));
		$this->view->listview = $listview;			
		
		$this->view->render();
	}
	
	public function item(){
		$this->layout_template = false;
		$id = $this->input->get('id', 'intval');
		$sql = new Sql();
		$root = $sql->from('messages', 'm')
			->joinLeft('users', 'u', 'm.user_id = u.id', 'username,nickname,realname,avatar')
			->joinLeft('users', 'u2', 'm.target = u2.id', 'username AS target_username,nickname AS target_nickname,realname AS target_realname')
			->where(array(
				'm.id = ?'=>$id,
			))
			->fetchRow()
		;
		
		$replies = $sql->from('messages', 'm')
			->joinLeft('users', 'u', 'm.user_id = u.id', 'username,nickname,realname,avatar')
			->joinLeft('messages', 'm2', 'm.parent = m2.id')
			->joinLeft('users', 'u2', 'm2.user_id = u2.id', 'username AS parent_username,nickname AS parent_nickname,realname AS parent_realname')
			->where(array(
				'm.root = '.$root['id'],
				'm.deleted = 0',
			))
			->order('id DESC')
			->fetchAll()
		;
		
		echo json_encode(array(
			'status'=>1,
			'data'=>array(
				'root'=>$root,
				'replies'=>$replies,
			),
		));
	}
	
}