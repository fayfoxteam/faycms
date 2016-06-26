<?php
namespace fay\models\feed;

use fay\core\Model;
use fay\models\tables\FeedsFiles;

class File extends Model{
	/**
	 * 默认返回字段
	 */
	private $default_fields = array('file_id', 'description');
	
	/**
	 * @param string $class_name
	 * @return File
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取动态附件
	 * @param int $feed_id 动态ID
	 * @param string $fields 附件字段（feeds_files表字段）
	 * @return array 返回包含动态附件信息的二维数组
	 */
	public function get($feed_id, $fields = null){
		if(empty($fields) || empty($fields[0])){
			//若传入$fields为空，则返回默认字段
			$fields = $this->default_fields;
		}
		$files = FeedsFiles::model()->fetchAll(array(
			'feed_id = ?'=>$feed_id,
		), $fields, 'sort');
		foreach($files as &$f){
			$f['url'] = \fay\services\File::getUrl($f['file_id']);
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
		if(empty($fields) || empty($fields[0])){
			//若传入$fields为空，则返回默认字段
			$fields = $this->default_fields;
		}
		//批量搜索，必须先得到feed_id
		if(!is_array($fields)){
			$fields = explode(',', $fields);
		}
		if(!in_array('feed_id', $fields)){
			$fields[] = 'feed_id';
			$remove_feed_id_field = true;
		}else{
			$remove_feed_id_field = false;
		}
		$files = FeedsFiles::model()->fetchAll(array(
			'feed_id IN (?)'=>$feed_id,
		), $fields, 'feed_id, sort');
		$return = array_fill_keys($feed_id, array());
		foreach($files as $f){
			$p = $f['feed_id'];
			if($remove_feed_id_field){
				unset($f['feed_id']);
			}
			$f['url'] = \fay\services\File::getUrl($f['file_id']);
			$return[$p][] = $f;
		}
		return $return;
	}
}