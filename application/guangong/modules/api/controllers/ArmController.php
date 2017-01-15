<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\services\FileService;
use guangong\models\tables\GuangongArmsTable;
use guangong\models\tables\GuangongUserExtraTable;

class ArmController extends ApiController{
	/**
	 * 兵种列表
	 */
	public function listAction(){
		$arms = GuangongArmsTable::model()->fetchAll(
			array('enabled = 1'),
			'id,name,picture',
			'sort, id DESC'
		);
		
		foreach($arms as $k => $arm){
			$arms[$k]['picture'] = FileService::get(
				$arm['picture'],
				array(
					'spare'=>'none',
				),
				'id,url'
			);
		}
		
		Response::json($arms);
	}
	
	/**
	 * 选定兵种
	 * @parameter int $arm_id 兵种ID
	 */
	public function set(){
		//登录检查
		$this->checkLogin();
		
		$this->form()->setData($this->input->post())
			->setRules(array(
				array('arm_id', 'required'),
				array('arm_id', 'exist', array(
					'table'=>GuangongArmsTable::model()->getTableName(),
					'field'=>'id',
				)),
			))->check();
		
		$arm = GuangongArmsTable::model()->find($this->form()->getData('arm_id'), 'id,enabled');
		if(!$arm['enabled']){
			Response::notify('error', array(
				'message'=>'指定兵种不存在',
				'code'=>'invalid-parameter:arm_id-is-not-exist',
			));
		}
		
		$userExtra = GuangongUserExtraTable::model()->find($this->current_user, 'arm_id');
		if($userExtra['arm_id']){
			Response::notify('error', array(
				'message'=>'您已设置过兵种，不能重复设置',
				'code'=>'arm-already-set'
			));
		}
		
		GuangongUserExtraTable::model()->update(array(
			'arm_id'=>$arm['id'],
		), array(
			'user_id = ?'=>$this->current_user
		));
		
		Response::notify('success', '兵种设置成功');
	}
}