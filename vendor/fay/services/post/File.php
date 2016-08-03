<?php
namespace fay\services\post;

use fay\core\Service;
use fay\models\tables\PostsFiles;

class File extends Service{
	/**
	 * 默认返回字段
	 */
	public static $default_fields = array('file_id', 'description', 'is_image');
	
	/**
	 * @param string $class_name
	 * @return File
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 获取文章附件
	 * @param int $post_id 文章ID
	 * @param string $fields 附件字段（posts_files表字段）
	 * @return array 返回包含文章附件信息的二维数组
	 */
	public function get($post_id, $fields = null){
		if(empty($fields) || empty($fields[0])){
			//若传入$fields为空，则返回默认字段
			$fields = self::$default_fields;
		}
		$files = PostsFiles::model()->fetchAll(array(
			'post_id = ?'=>$post_id,
		), $fields, 'sort');
		foreach($files as &$f){
			$f['url'] = \fay\services\File::getUrl($f['file_id']);
		}
		return $files;
	}
	
	/**
	 * 批量获取文章附件
	 * @param array $post_id 文章ID构成的二维数组
	 * @param string $fields 附件字段（posts_files表字段）
	 * @return array 返回以文章ID为key的三维数组
	 */
	public function mget($post_id, $fields = null){
		if(empty($fields) || empty($fields[0])){
			//若传入$fields为空，则返回默认字段
			$fields = self::$default_fields;
		}
		//批量搜索，必须先得到post_id
		if(!is_array($fields)){
			$fields = explode(',', $fields);
		}
		if(!in_array('post_id', $fields)){
			$fields[] = 'post_id';
			$remove_post_id_field = true;
		}else{
			$remove_post_id_field = false;
		}
		$files = PostsFiles::model()->fetchAll(array(
			'post_id IN (?)'=>$post_id,
		), $fields, 'post_id, sort');
		$return = array_fill_keys($post_id, array());
		foreach($files as $f){
			$p = $f['post_id'];
			if($remove_post_id_field){
				unset($f['post_id']);
			}
			$f['url'] = \fay\services\File::getUrl($f['file_id']);
			$return[$p][] = $f;
		}
		return $return;
	}
}