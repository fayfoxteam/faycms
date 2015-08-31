<?php
namespace fay\widgets\jq_camera\controllers;

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
		$this->setConfig($data);
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
			array(array('transPeriod', 'time'), 'int'),
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
			'height'=>'trim',
			'transPeriod'=>'intval',
			'time'=>'intval',
			'fx'=>'trim',
		);
	}
}