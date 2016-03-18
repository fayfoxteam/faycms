<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\Setting;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\Contacts;
use fay\models\tables\Actionlogs;
use fay\core\Response;
use fay\helpers\Html;
use fay\core\Loader;

class ContactController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'message';
	}
	
	public function index(){
		$this->layout->subtitle = '用户留言';
		//用户自定义项
		$this->layout->_setting_panel = '_setting_index';
		$_setting_key = 'admin_contact_index';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array(
			'cols'=>array('realname', 'email', 'phone', 'create_time', 'area'),
			'display_time'=>'short',
			'page_size'=>10,
		);
		$this->form('setting')->setModel(Setting::model())
			->setJsModel('setting')
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key,
			));
		
		if(in_array('ip', $_settings['cols'])){
			//引入IP地址库
			Loader::vendor('IpLocation/IpLocation.class');
			$this->view->iplocation = new \IpLocation();
		}
		
		$sql = new Sql();
		$sql->from('contacts')
			->order('id DESC');
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>$this->form('setting')->getData('page_size', 10),
		));
		
		//引入IP地址库
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		$this->view->render();
	}
	
	public function setRead(){
		$id = $this->input->get('id', 'intval');
		Contacts::model()->update(array(
			'is_read'=>1,
		), $id);
		
		$this->actionlog(Actionlogs::TYPE_CONTACT, '一条留言被标记为已读', $id);
		
		Response::notify('success', array(
			'message'=>'一条留言被标记为已读 - '.Html::link('撤销', array('admin/contact/set-unread', array(
				'id'=>$id,
			))),
		));
	}
	
	public function setUnread(){
		$id = $this->input->get('id', 'intval');
		Contacts::model()->update(array(
			'is_read'=>0,
		), $id);
		
		$this->actionlog(Actionlogs::TYPE_CONTACT, '一条留言被标记为未读', $id);
		
		Response::notify('success', array(
			'message'=>'一条留言被标记为未读 - '.Html::link('撤销', array('admin/contact/set-read', array(
				'id'=>$id,
			))),
		));
	}
	
	public function remove(){
		$id = $this->input->get('id', 'intval');
		Contacts::model()->delete($id);
		
		$this->actionlog(Actionlogs::TYPE_CONTACT, '一条留言被永久删除', $id);
		
		Response::notify('success', array(
			'message'=>'一条留言被永久删除',
		));
	}
	
	public function reply(){
		$id = $this->input->request('id', 'intval');
		$reply = $this->input->request('reply', 'trim');
		Contacts::model()->update(array(
			'reply'=>$reply,
			'is_read'=>1,
		), $id);
		
		$contact = Contacts::model()->find($id, 'id,reply');
		$this->actionlog(Actionlogs::TYPE_CONTACT, '回复了一条留言', $id);
		
		Response::notify('success', array(
			'message'=>'回复成功',
			'data'=>array(
				'id'=>$contact['id'],
				'reply'=>$contact['reply'],
			)
		));
	}
	
	public function batch(){
		$action = $this->input->post('action');
		$ids = $this->input->post('ids', 'intval');
		
		if($action && $ids){
			if($action == 'read'){
				Contacts::model()->update(array(
					'is_read'=>1,
				), array(
					'id IN (?)'=>$ids,
				));
			}else if($action == 'unread'){
				Contacts::model()->update(array(
					'is_read'=>0,
				), array(
					'id IN (?)'=>$ids,
				));
			}else if($action == 'remove'){
				Contacts::model()->delete(array(
					'id IN (?)'=>$ids,
				));
			}
		}
		Response::notify('success', array(
			'message'=>'',
		));
	}
	
	public function edit(){
		
	}
}