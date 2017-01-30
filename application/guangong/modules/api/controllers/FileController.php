<?php
namespace guangong\modules\api\controllers;

use fay\core\Response;
use fay\services\FileService;

class FileController extends \cms\modules\api\controllers\FileController{
	/**
	 * 从指定链接获取图片存到本地
	 */
	public function uploadFromUrl(){
		//表单验证
		$this->form()->setRules(array(
			array(array('url'), 'required'),
			array(array('url'), 'url'),
			array(array('p', 'range', array('range'=>array('0', '1'))))
		))->setFilters(array(
			'url'=>'trim',
			'cat'=>'trim',
			'p'=>'intval',
			'client_name'=>'trim',
		))->setLabels(array(
			'url'=>'链接地址',
			'cat'=>'分类',
			'p'=>'私密空间',
			'client_name'=>'文件名',
		))->check();
		
		set_time_limit(0);
		$url = $this->form()->getData('url');
		$cat = $this->form()->getData('cat');
		$private = !!$this->form()->getData('p');
		$client_name = $this->form()->getData('client_name');
		
		$data = FileService::service()->uploadFromUrl($url, $cat, $client_name, $private);
		Response::json($data);
	}
}