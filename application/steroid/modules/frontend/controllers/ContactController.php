<?php
namespace steroid\modules\frontend\controllers;

use steroid\library\FrontController;
use steroid\models\forms\LeaveMessage;
use fay\core\Response;
use fay\models\tables\Contacts;
use fay\helpers\Request;

class ContactController extends FrontController{
	public function send(){
		$this->form()->setModel(LeaveMessage::model());
		
		if($this->input->post()){
			if($this->form()->check()){
				Contacts::model()->insert(array(
					'name'=>$this->form()->getData('name'),
					'email'=>$this->form()->getData('email'),
					'mobile'=>$this->form()->getData('phone'),
					'content'=>$this->form()->getData('message'),
					'ip_int'=>Request::ip2int($this->ip),
					'show_ip_int'=>Request::ip2int($this->ip),
					'create_time'=>$this->current_time,
					'publish_time'=>$this->current_time,
				));
				Response::notify('success', 'Message has been send.');
			}else{
				Response::notify('error', $this->form()->getFirstError());
			}
		}else{
			Response::notify('error', 'No data submitted.');
		}
	}
}