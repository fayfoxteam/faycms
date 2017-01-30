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
				'message'=>'您已设置过勤务，不能重复设置',
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
		
		Response::notify('success', array(
			'message'=>'勤务设置成功',
			'data'=>$hour,
		));
	}
	
	/**
	 * 获取一个时辰描述
	 */
	public function get(){
		//表单验证
		$this->form()->setRules(array(
			array(array('hour_id'), 'required'),
			array(array('hour_id'), 'int', array('min'=>1)),
			array(array('hour_id'), 'exist', array(
				'table'=>'guangong_hours',
				'field'=>'id',
			)),
		))->setFilters(array(
			'id'=>'intval',
		))->setLabels(array(
			'id'=>'时辰ID',
		))->check();
		
		$hour = GuangongHoursTable::model()->find($this->form()->getData('hour_id'));
		if(!$hour){
			Response::notify('error', '指定时辰不存在');
		}
		
		Response::json($hour);
	}
}