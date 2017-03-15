<?php
namespace guangong\modules\frontend\controllers;

use fay\core\Response;
use fay\services\OptionService;
use fay\services\wechat\core\AccessToken;
use fay\services\wechat\jssdk\JsSDK;
use guangong\library\FrontController;
use guangong\models\tables\GuangongSpeaksTable;

/**
 * 代言
 */
class SpeakController extends FrontController{
	public function __construct()
	{
		parent::__construct();
		
		$this->checkLogin();
		$this->layout->title = '代言';
	}
	
	public function index(){
		
		$this->view->renderPartial();
	}
	
	public function shared(){
		//表单验证
		$this->form()->setRules(array(
			array(array('id'), 'required'),
			array(array('id'), 'int', array('min'=>1)),
			array(array('id'), 'exist', array(
				'table'=>'guangong_speaks',
				'field'=>'id',
			)),
		))->setFilters(array(
			'id'=>'intval',
		))->setLabels(array(
			'id'=>'代言ID',
		))->check();
		
		$speak = GuangongSpeaksTable::model()->find($this->form()->getData('id'));
		
		$app_config = OptionService::getGroup('oauth:weixin');
		$access_token = new AccessToken($app_config['app_id'], $app_config['app_secret']);
		
		$this->view->renderPartial(null, array(
			'speak'=>$speak,
			'access_token'=>$access_token->getToken(),
		));
	}
	
	public function create(){
		if($this->input->post() && $this->form()->setModel(GuangongSpeaksTable::model())
			->check()){
			$data = $this->form()->getAllData();
			$data['create_time'] = $this->current_time;
			
			$speak_id = GuangongSpeaksTable::model()->insert($data);
			Response::notify('success', array(
				'message'=>'代言成功',
			), array(
				'speak/shared', array('id'=>$speak_id),
			));
		}
		
		$app_config = OptionService::getGroup('oauth:weixin');
		
		$js_sdk = new JsSDK($app_config['app_id'], $app_config['app_secret']);
		
		$access_token = new AccessToken($app_config['app_id'], $app_config['app_secret']);
		$this->view->renderPartial('create', array(
			'js_sdk_config'=>$js_sdk->getConfig(array('chooseImage', 'uploadImage')),
			'access_token'=>$access_token->getToken(),
		));
	}
}