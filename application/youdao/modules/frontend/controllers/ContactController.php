<?php
namespace youdao\modules\frontend\controllers;

use youdao\library\FrontController;
use fay\models\tables\Pages;
use fay\models\Option;

class ContactController extends FrontController{
	public $layout_template = 'inner';
	
	public function index(){
		$page = Pages::model()->fetchRow(array('alias = ?'=>'contact'));
		
		Pages::model()->inc($page['id'], 'views', 1);
		$this->view->page = $page;

		$this->layout->submenu = array(
			array(
				'title'=>'联系我们',
				'link'=>'#',
				'class'=>'sel',
			),
			array(
				'title'=>'联系方式',
				'link'=>'#contact-info',
				'class'=>'',
			),
			array(
				'title'=>'地理位置',
				'link'=>'#location',
				'class'=>'',
			),
			array(
				'title'=>'在线留言',
				'link'=>'#message-online',
				'class'=>'',
			),
		);
		$this->layout->subtitle = '公司概况';
		$this->layout->breadcrumbs = array(
			array(
				'title'=>'首页',
				'link'=>$this->view->url(),
			),
			array(
				'title'=>'关于有道',
				'link'=>$this->view->url('about'),
			),
			array(
				'title'=>'企业简介',
			),
		);
		$this->layout->banner = 'contact-banner.jpg';
		$this->layout->current_directory = 'contact';

		$this->layout->title = Option::get('site.seo_contact_title');
		$this->layout->keywords = Option::get('site.seo_contact_keywords');
		$this->layout->description = Option::get('site.seo_contact_description');
		
		$this->view->render();
	}
	
	public function markmessage(){
		sleep(3);
// 		Email::model()->send('369281831@qq.com', '网站留言', "姓名：{$this->input->post('realname')}<br />
// 			联系电话：{$this->input->post('phone')}<br />
// 			邮箱：{$this->input->post('email')}<br />
// 			单位：{$this->input->post('company')}<br />
// 			留言：{$this->input->post('message')}<br />
// 		");
	}
}