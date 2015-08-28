<?php
namespace fay\widgets\jq_nivo_slider\controllers;

use fay\core\Widget;
use fay\models\Flash;

class AdminController extends Widget{
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
		Flash::set('编辑成功', 'success');
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
			array(array('animSpeed', 'pauseTime', 'width', 'height'), 'int', array('min'=>1)),
			array('id', 'required'),
			array('id', 'string', array('format'=>'alias')),
		);
	}
	
	public function labels(){
		return array(
			'id'=>'外层元素ID',
			'links'=>'链接地址',
			'pauseTime'=>'停顿时长',
			'animSpeed'=>'过渡动画时长',
			'width'=>'图片宽度',
			'height'=>'图片高度',
		);
	}
	
	public function filters(){
		return array(
			'animSpeed'=>'intval',
			'pauseTime'=>'intval',
			'effect'=>'trim',
			'elementId'=>'trim',
			'directionNav'=>'intval',
			'width'=>'intval',
			'height'=>'intval',
		);
	}
}