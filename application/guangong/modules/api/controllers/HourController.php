<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\services\FileService;
use guangong\models\tables\GuangongHoursTable;
use guangong\models\tables\GuangongUserExtraTable;

class HourController extends ApiController{
	/**
	 * 时辰列表
	 */
	public function listAction(){
		$hours = GuangongHoursTable::model()->fetchAll(
			array(),
			'*',
			'id'
		);
		
		Response::json($hours);
	}
	
	/**
	 * 选定时辰（随机）
	 */
	public function set(){
		//登录检查
		$this->checkLogin();
		
		$userExtra = GuangongUserExtraTable::model()->find($this->current_user, 'hour_id');
		if($userExtra['hour_id']){
			Response::notify('error', array(
				'message'=>'您已设置过时辰，不能重复设置',
				'code'=>'arm-already-set'
			));
		}
		
		//随机一个时辰
		$hour = GuangongHoursTable::model()->fetchRow(array(), 'id', 'RAND()');
		
		GuangongUserExtraTable::model()->update(array(
			'hour_id'=>$hour['id'],
		), array(
			'user_id = ?'=>$this->current_user
		));
		
		Response::notify('success', '时辰设置成功');
	}
}