<?php
namespace valentine\modules\frontend\controllers;

use cms\library\ApiController;
use fay\services\FileService;
use valentine\models\tables\ValentineUserTeamsTable;

/**
 * 测试Controller，上线前删除或禁用
 */
class TestController extends ApiController{
	//登录指定用户
	public function setOpenId(){
		\F::session()->set('open_id', 'ohBiqv7DwPPlhtyDF6hH2gpPQkqE');
	}
	
	public function export(){
		set_time_limit(0);
			
		$teams = ValentineUserTeamsTable::model()->fetchAll();
		FileService::createFolder(APPLICATION_PATH . 'runtimes/files');
		
		foreach($teams as $team){
			$file_path = FileService::getPath($team['photo']);
			if(!$file_path){
				dump($team);die;
			}
			if(!file_exists($file_path)){
				dump($file_path);die;
			}
			copy($file_path, APPLICATION_PATH . "runtimes/files/{$team['id']}-{$team['name']}-{$team['type']}-{$team['votes']}.jpg");
		}
	}
}