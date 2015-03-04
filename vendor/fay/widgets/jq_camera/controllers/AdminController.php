<?php
namespace fay\widgets\jq_camera\controllers;

use fay\core\Widget;

class AdminController extends Widget{
	
	public $title = '轮播图 - camera';
	public $author = 'fayfox';
	public $author_link = 'http://www.fayfox.com';
	public $description = '轮播图 - jquery camera插件（自适应全屏轮播）';
	
	public function index($data){
		$this->view->data = $data;
		$this->view->render();
	}
	
	public function onPost(){
		$data = $this->form->getFilteredData();
		
		$photos = $this->input->post('photos', 'intval', array());
		$links = $this->input->post('links', 'trim');
		$titles = $this->input->post('titles', 'trim');
		foreach($photos as $p){
			$data['files'][] = array(
				'file_id'=>$p,
				'link'=>$links[$p],
				'title'=>$titles[$p],
			);
		}
		$this->saveData($data);
		$this->flash->set('编辑成功', 'success');
	}
	
	/**
	 * 会在编辑界面的侧边栏出现
	 * @param array $data 该widget实例的参数
	 */
	public function sidebar($data){
		$this->view->data = $data;
		
		$this->view->render('sidebar');
	}
	
	public function rules(){
		return array(
			array('links', 'url'),
			array(array('height', 'transPeriod', 'time'), 'int'),
		);
	}
	
	public function labels(){
		return array(
			'links'=>'链接地址',
			'height'=>'高度',
			'transPeriod'=>'过渡动画时长',
			'time'=>'播放间隔时长',
		);
	}
	
	public function filters(){
		return array(
			'height'=>'intval',
			'transPeriod'=>'intval',
			'time'=>'intval',
			'fx'=>'trim',
		);
	}
}