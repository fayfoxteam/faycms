<?php
namespace fay\models\post;

use fay\core\Model;
use fay\models\tables\PostsFiles;

class File extends Model{
	/**
	 * @return File
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取文章附件
	 * @param int $post_id 文章ID
	 * @param string $fields 附件字段（posts_files表字段）
	 * @return array 返回包含文章附件信息的二维数组
	 */
	public function get($post_id, $fields = 'file_id,description,is_image'){
		$files = PostsFiles::model()->fetchAll(array(
			'post_id = ?'=>$post_id,
		), $fields, 'sort');
		foreach($files as &$f){
			$f['url'] = File::getUrl($f['file_id']);
		}
		return $files;
	}
	
	/**
	 * 批量获取文章附件
	 * @param array $post_id 文章ID构成的二维数组
	 * @param string $fields 附件字段（posts_files表字段）
	 * @return array 返回以文章ID为key的三维数组
	 */
	public function mget($post_id, $fields = 'file_id,description,is_image'){
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
			$files = PostsFiles::model()->fetchAll(array(
				'post_id IN (?)'=>$post_id,
			), $fields, 'post_id, sort');
			$return = array_fill_keys($post_id, array());
			foreach($files as $f){
				$p = $f['post_id'];
				if($remove_post_id){
					unset($f['post_id']);
				}
				$f['url'] = File::getUrl($f['file_id']);
				$return[$p][] = $f;
			}
			return $return;
	}
}