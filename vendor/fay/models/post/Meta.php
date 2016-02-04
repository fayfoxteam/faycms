<?php
namespace fay\models\post;

use fay\core\Model;
use fay\models\tables\PostMeta;

class Meta extends Model{
	/**
	 * @return Meta
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取文章计数信息
	 * @param int $post_id 文章ID
	 * @param string $fields 字段（post_meta表字段）
	 * @return array 返回包含文章meta信息的一维数组
	 */
	public function get($post_id, $fields = 'comments,views,likes'){
		return PostMeta::model()->fetchRow(array(
			'post_id = ?'=>$post_id,
		), $fields);
	}
	
	/**
	 * 批量获取文章计数信息
	 * @param array $post_ids 文章ID一维数组
	 * @param string $fields 字段（post_meta表字段）
	 * @return array 返回以文章ID为key的二维数组
	 */
	public function mget($post_ids, $fields = 'comments,views,likes'){
		//批量搜索，必须先得到post_id
		if(!is_array($fields)){
			$fields = explode(',', $fields);
		}
		if(!in_array('post_id', $fields)){
			$fields[] = 'post_id';
			$remove_post_id = true;
		}else{
			$remove_post_id = false;
		}
		$metas = PostMeta::model()->fetchAll(array(
			'post_id IN (?)'=>$post_ids,
		), $fields, 'post_id');
		$return = array_fill_keys($post_ids, array());
		foreach($metas as $m){
			$p = $m['post_id'];
			if($remove_post_id){
				unset($m['post_id']);
			}
			$return[$p] = $m;
		}
		return $return;
	}
}