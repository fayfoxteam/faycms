<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\services\FileService;
use guangong\models\tables\GuangongRanksTable;

class RankController extends ApiController{
	/**
	 * 列出系统预定义誓言
	 */
	public function get(){
		//表单验证
		$this->form()->setRules(array(
			array(array('rank_id'), 'required'),
			array(array('rank_id'), 'int', array('min'=>1)),
			array(array('rank_id'), 'exist', array(
				'table'=>'guangong_ranks',
				'field'=>'id',
			)),
		))->setFilters(array(
			'id'=>'intval',
		))->setLabels(array(
			'id'=>'军衔ID',
		))->check();
		
		$rank_id = $this->form()->getData('rank_id');
		$rank = GuangongRanksTable::model()->find($rank_id);
		
		$rank['description_picture'] = FileService::get($rank['description_picture']);
		
		Response::json($rank);
	}
}