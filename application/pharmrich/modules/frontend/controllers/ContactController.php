<?php
namespace pharmrich\modules\frontend\controllers;

use pharmrich\library\FrontController;
use fay\models\tables\Pages;
use pharmrich\models\forms\LeaveMessage;
use fay\core\Response;
use fay\models\tables\Contacts;
use fay\helpers\Request;

class ContactController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->current_header_menu = 'contact';
	}
	
	public function index(){
		$page = Pages::model()->fetchRow(array('alias = ?'=>'contact'));
		Pages::model()->inc($page['id'], 'views', 1);
		$this->view->page = $page;

		$this->layout->title = $page['seo_title'] ? $page['seo_title'] : $page['title'];
		$this->layout->keywords = $page['seo_keywords'];
		$this->layout->description = $page['seo_description'];
		
		$this->form()->setModel(LeaveMessage::model());
		
		$this->view->render();
	}
	
	public function send(){
		$this->form()->setModel(LeaveMessage::model());
		
		if($this->input->post()){
			if($this->form()->check()){
				Contacts::model()->insert(array(
					'name'=>$this->form()->getData('name'),
					'email'=>$this->form()->getData('email'),
					'title'=>$this->form()->getData('subject'),
					'content'=>$this->form()->getData('message'),
					'ip_int'=>Request::ip2int($this->ip),
					'create_time'=>$this->current_time,
				));
				Response::notify('success', 'Message has been send.');
			}
		}else{
			Response::notify('error', 'No data submitted.');
		}
	}
}