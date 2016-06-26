<?php
namespace shinecolor\modules\frontend\controllers;

use shinecolor\library\FrontController;
use fay\models\tables\Contacts;
use fay\services\Flash;

class ContactController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
		
		$this->layout->current_header_menu = 'contact';
	}
	
	public function index(){
		if($this->input->post()){
			Contacts::model()->insert(array(
				'realname'=>$this->input->post('realname'),
				'email'=>$this->input->post('email'),
				'phone'=>$this->input->post('phone'),
				'content'=>$this->input->post('content'),
				'create_time'=>$this->current_time,
				'ip'=>$this->ip,
			));
			Flash::set('留言发布成功', 'success');
		}
		$this->layout->breadcrumbs = array(
			array(
				'label'=>'首页',
				'link'=>$this->view->url(),
			),
			array(
				'label'=>'联系我们',
			),
		);
		
		$this->view->render();
	}
	
}