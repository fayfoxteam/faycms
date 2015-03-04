<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\Option;

class SiteController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'site';
	}
	
	public function options(){
		$this->layout->subtitle = '站点参数';
	
		if($this->input->post()){
			foreach($this->input->post() as $key=>$value){
				Option::set($key, $value);
			}
			$this->flash->set('更新成功', 'success');
		}
	
		$this->view->render();
	}
}