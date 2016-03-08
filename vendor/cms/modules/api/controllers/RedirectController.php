<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;

class RedirectController extends ApiController{
	/**
	 * 跳转到指定url
	 */
	public function index(){
		//表单验证
		$this->form()->setRules(array(
			array('url', 'required'),
			array('url', 'url'),
		))->setFilters(array(
			'url'=>'trim|base64_decode',
		))->check();
		
		header('location:'.$this->form()->getData('url'));
		die;
	}
}