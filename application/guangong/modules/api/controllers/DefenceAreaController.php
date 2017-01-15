<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\services\FileService;
use guangong\models\tables\GuangongDefenceAreasTable;
use guangong\models\tables\GuangongUserExtraTable;

class DefenceAreaController extends ApiController{
	/**
	 * 防区列表
	 */
	public function listAction(){
		$areas = GuangongDefenceAreasTable::model()->fetchAll(
			array('enabled = 1'),
			'id,name,picture',
			'sort, id DESC'
		);
		
		foreach($areas as $k => $area){
			$areas[$k]['picture'] = FileService::get(
				$area['picture'],
				array(
					'spare'=>'none',
				),
				'id,url'
			);
		}
		
		Response::json($areas);
	}
	
	/**
	 * 选定防区
	 * @parameter int $area_id 防区ID
	 */
	public function set(){
		//登录检查
		$this->checkLogin();
		
		$this->form()->setData($this->input->post())
			->setRules(array(
				array('area_id', 'required'),
				array('area_id', 'exist', array(
					'table'=>GuangongDefenceAreasTable::model()->getTableName(),
					'field'=>'id',
				)),
			))->check();
		
		$area = GuangongDefenceAreasTable::model()->find($this->form()->getData('area_id'), 'id,enabled');
		if(!$area['enabled']){
			Response::notify('error', array(
				'message'=>'指定防区不存在',
				'code'=>'invalid-parameter:area_id-is-not-exist',
			));
		}
		
		$userExtra = GuangongUserExtraTable::model()->find($this->current_user, 'defence_area_id');
		if($userExtra['defence_area_id']){
			Response::notify('error', array(
				'message'=>'您已设置过防区，不能重复设置',
				'code'=>'arm-already-set'
			));
		}
		
		GuangongUserExtraTable::model()->update(array(
			'defence_area_id'=>$area['id'],
		), array(
			'user_id = ?'=>$this->current_user
		));
		
		Response::notify('success', '防区设置成功');
	}
}