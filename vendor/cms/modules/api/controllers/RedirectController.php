<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;

/**
 * 跳转
 */
class RedirectController extends ApiController{
	/**
	 * 跳转到指定url
	 * 用一个本站链接跳转到站外链接，SEO会比较好一些。
	 * 必要情况下，也可以通过这个跳转来做一些统计
	 * @param string $url
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