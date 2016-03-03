<?php
namespace fay\models\feed;

use fay\core\Model;
use fay\models\tables\FeedMeta;

class Meta extends Model{
	/**
	 * 默认返回字段
	 */
	private $default_fields = array('comments', 'views', 'likes');
	
	/**
	 * @return Meta
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取动态计数信息
	 * @param int $feed_id 动态ID
	 * @param string $fields 字段（feed_meta表字段）
	 * @return array 返回包含动态meta信息的一维数组
	 */
	public function get($feed_id, $fields = null){
		if(empty($fields) || empty($fields[0])){
			//若传入$fields为空，则返回默认字段
			$fields = $this->default_fields;
		}
		return FeedMeta::model()->fetchRow(array(
			'feed_id = ?'=>$feed_id,
		), $fields);
	}
	
	/**
	 * 批量获取动态计数信息
	 * @param array $feed_ids 动态ID一维数组
	 * @param string $fields 字段（feed_meta表字段）
	 * @return array 返回以动态ID为key的二维数组
	 */
	public function mget($feed_ids, $fields = null){
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
			$remove_feed_id = true;
		}else{
			$remove_feed_id = false;
		}
		$metas = FeedMeta::model()->fetchAll(array(
			'feed_id IN (?)'=>$feed_ids,
		), $fields, 'feed_id');
		$return = array_fill_keys($feed_ids, array());
		foreach($metas as $m){
			$p = $m['feed_id'];
			if($remove_feed_id){
				unset($m['feed_id']);
			}
			$return[$p] = $m;
		}
		return $return;
	}
}