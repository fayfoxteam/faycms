<?php
namespace steroid\modules\frontend\controllers;

use steroid\library\FrontController;
use steroid\models\forms\LeaveMessage;
use fay\core\Response;
use cms\models\tables\ContactsTable;

class ContactController extends FrontController{
    public function send(){
        $this->form()->setModel(LeaveMessageTable::model());
        
        if($this->input->post()){
            if($this->form()->check()){
                ContactsTable::model()->insert(array(
                    'name'=>$this->form()->getData('name'),
                    'email'=>$this->form()->getData('email'),
                    'mobile'=>$this->form()->getData('phone'),
                    'content'=>$this->form()->getData('message'),
                    'ip_int'=>$this->ip_int,
                    'show_ip_int'=>$this->ip_int,
                    'create_time'=>$this->current_time,
                    'publish_time'=>$this->current_time,
                ));
                return Response::notify(Response::NOTIFY_SUCCESS, 'Message has been send.');
            }else{
                return Response::notify(Response::NOTIFY_FAIL, $this->form()->getFirstError());
            }
        }else{
            return Response::notify(Response::NOTIFY_FAIL, 'No data submitted.');
        }
    }
}