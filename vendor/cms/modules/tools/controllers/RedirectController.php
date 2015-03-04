<?php
namespace cms\modules\tools\controllers;

use cms\library\ToolsController;
use fay\core\HttpException;

class RedirectController extends ToolsController{
	/**
	 * 跳转到指定url
	 */
	public function index(){
		$url = base64_decode($this->input->get('url', 'trim'));
		if($this->form()->setRules(array(
			array('url', 'required'),
			array('url', 'url'),
		))->setFilters(array(
			'url'=>'trim|base64_decode',
		))->check()){
			header('location:'.$this->form()->getData('url'));
			die;
		}else{
			throw new HttpException('参数异常', 500);
		}
	}
}