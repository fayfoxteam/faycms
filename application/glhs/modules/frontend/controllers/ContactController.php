<?php
namespace glhs\modules\frontend\controllers;

use glhs\library\FrontController;
use fay\models\Email;
use fay\models\tables\Pages;
use fay\core\Response;
use fay\models\Flash;

class ContactController extends FrontController{
	public function index(){
		$page = Pages::model()->fetchRow(array('alias = ?'=>'contact'));
		Pages::model()->inc($page['id'], 'views', 1);
		$this->view->page = $page;

		$this->layout->title = $page['seo_title'] ? $page['seo_title'] : $page['title'];
		$this->layout->keywords = $page['seo_keywords'];
		$this->layout->description = $page['seo_description'];
		
		$this->view->render();
	}
	
	public function markmessage(){
		Email::send('369281831@qq.com', '网站留言', "
			称呼：{$this->input->post('name')}<br />
			联系电话：{$this->input->post('phone')}<br />
			邮箱：{$this->input->post('email')}<br />
			留言：{$this->input->post('message')}<br />
		");
		Flash::set('留言邮件已发送', 'success');
		Response::goback();
	}
}