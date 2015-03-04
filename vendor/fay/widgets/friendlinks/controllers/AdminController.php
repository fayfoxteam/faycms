<?php
namespace fay\widgets\friendlinks\controllers;

use fay\core\Widget;

class AdminController extends Widget{
	
	public $title = '友情链接';
	public $author = 'fayfox';
	public $author_link = 'http://www.fayfox.com';
	public $description = '友情链接列表';
	
	public function index($data){
		//获取默认模版
		if(empty($data['template'])){
			$data['template'] = file_get_contents(dirname(__FILE__).'/../views/index/template.php');
		}
		
		$this->view->data = $data;
		$this->view->render();
	}
	
	/**
	 * 当有post提交的时候，会自动调用此方法
	 */
	public function onPost(){
		$uri = 'cat/{$id}';
		if($this->input->post('uri')){
			$uri = $this->input->post('uri');
		}else if($this->input->post('other_uri')){
			$uri = $this->input->post('other_uri');
		}
		$this->saveData(array(
			'title'=>$this->input->post('title', null, ''),
			'number'=>$this->input->post('number', 'intval', 5),
			'template'=>$this->input->post('template'),
		));
		$this->flash->set('编辑成功', 'success');
	}
	
}