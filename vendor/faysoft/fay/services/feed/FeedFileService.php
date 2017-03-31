<?php
namespace fay\services\feed;

use fay\core\Service;
use fay\helpers\FieldHelper;
use fay\models\tables\FeedsFilesTable;
use fay\services\file\FileService;

class FeedFileService extends Service{
	/**
	 * 默认返回字段
	 */
	public static $default_fields = array('file_id', 'description');
	
	/**
	 * @param string $class_name
	 * @return FeedFileService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 获取动态附件
	 * @param int $feed_id 动态ID
	 * @param string $fields 附件字段（feeds_files表字段）
	 * @return array 返回包含动态附件信息的二维数组
	 */
	public function get($feed_id, $fields = null){
		if(empty($fields)){
			//若传入$fields为空，则返回默认字段
			$fields = self::$default_fields;
		}
		$files = FeedsFilesTable::model()->fetchAll(array(
			'feed_id = ?'=>$feed_id,
		), $fields['fields'], 'sort');
		foreach($files as &$f){
			$f['url'] = FileService::getUrl($f['file_id']);
		}
		return $files;
	}
	
	/**
	 * 批量获取动态附件
	 * @param array $feed_id 动态ID构成的二维数组
	 * @param string $fields 附件字段（feeds_files表字段）
	 * @return array 返回以动态ID为key的三维数组
	 */
	public function mget($feed_id, $fields = null){
		if(empty($fields)){
			//若传入$fields为空，则返回默认字段
			$fields = self::$default_fields;
		}else if(!is_array($fields)){
			$fields = FieldHelper::parse($fields, 'files');
		}
		//批量搜索，必须先得到feed_id
		if(!is_array($fields)){
			$fields = explode(',', $fields);
		}
		if(!in_array('feed_id', $fields)){
			$fields['fields'][] = 'feed_id';
			$remove_feed_id_field = true;
		}else{
			$remove_feed_id_field = false;
		}
		$files = FeedsFilesTable::model()->fetchAll(array(
			'feed_id IN (?)'=>$feed_id,
		), $fields['fields'], 'feed_id, sort');
		$return = array_fill_keys($feed_id, array());
		foreach($files as $f){
			$p = $f['feed_id'];
			if($remove_feed_id_field){
				unset($f['feed_id']);
			}
			$f['url'] = FileService::getUrl($f['file_id']);
			$return[$p][] = $f;
		}
		return $return;
	}
}