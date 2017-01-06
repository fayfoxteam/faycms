<?php
namespace cms\widgets\change_app\controllers;

use fay\widget\Widget;
use fay\services\FileService;
use fay\core\Response;

class IndexController extends Widget{
	public function index(){
		$apps = FileService::getFileList(APPLICATION_PATH.'..');
		$options = array();
		foreach($apps as $app){
			if($app['is_dir']){
				$options[$app['name']] = $app['name'];
			}
		}
		$this->view->options = $options;
		$this->view->render();
	}
	
	public function change(){
		if($this->input->post('app')){
			setcookie('__app', $this->input->post('app'), null, '/');
			Response::redirect('admin/index/index');
		}
	}
}