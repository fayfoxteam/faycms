<?php
namespace apidoc\helpers;

use fay\core\Uri;

class TrackHelper{
	/**
	 * 获取接口状态
	 */
	public static function getTrackId(){
		$old_trackid = \F::input()->get('trackid', 'trim');
		
		$router = Uri::getInstance()->router;
		if($router == 'frontend/api/item'){
			//API详情页
			return 'a' . \F::input()->get('api_id', 'intval', 0);
		}else if($router == 'frontend/model/item'){
			//模型页
			if($old_trackid){
				return $old_trackid . '.m' . \F::input()->get('model_id', 'intval', 0);
			}else{
				return 'm' . \F::input()->get('model_id', 'intval', 0);
			}
		}
	}
	
	/**
	 * 在trackid中解析出API ID
	 * @return string
	 */
	public static function getApiId(){
		$trackid = \F::input()->get('trackid', 'trim');
		if($trackid){
			$tracks = explode('.', $trackid);
			if(preg_match('/^a(\d+)$/', $tracks[0])){
				return substr($tracks[0], 1);
			}
		}
		
		return null;
	}
	
	/**
	 * 获取trackid中包含的model id
	 * @return array
	 */
	public static function getTrackModels(){
		$trackid = \F::input()->get('trackid', 'trim');
		$tracks = explode('.', $trackid);
		$models = array();
		foreach($tracks as $t){
			if(preg_match('/^m(\d+)$/', $t)){
				$models[] = substr($t, 1);
			}
		}
		
		return $models;
	}
}