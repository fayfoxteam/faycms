<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\Option;
use fay\core\Response;

class SiteController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'site';
	}
	
	public function setOptions(){
		$this->layout->subtitle = '站点参数';
	
		if($this->input->post()){
			$data = $this->input->post();
			unset($data['_submit']);//提交按钮不用保存
			foreach($data as $key=>$value){
				Option::set($key, $value);
			}
			Response::output('success', '保存成功');
		}
		Response::output('error', '无数据提交');
	}
	
	/**
	 * 配置项汇总
	 */
	public function settings(){
		$this->layout->subtitle = '系统设置';
		
		$this->view->render();
	}
}