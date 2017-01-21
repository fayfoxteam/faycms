<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\helpers\ArrayHelper;
use guangong\models\tables\GuangongVowsTable;

class VowController extends ApiController{
	/**
	 * 列出系统预定义誓言
	 */
	public function listAction(){
		$vows = GuangongVowsTable::model()->fetchAll(array(
			'enabled = 1',
		), 'content', 'sort');
		
		Response::json(ArrayHelper::column($vows, 'content'));
	}
}