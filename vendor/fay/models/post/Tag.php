<?php
namespace fay\models\post;

use fay\core\Model;
use fay\core\Sql;
use fay\models\tables\Tags;

class Tag extends Model{
	/**
	 * @return Tag
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取文章对应tags
	 * @param int $post_id 文章ID
	 * @param string $fields 标签字段，tags表字段
	 * @return array 返回包含文章tag信息的二维数组
	 */
	public function get($post_id, $fields = 'id,title'){
		$sql = new Sql();
		return $sql->from('posts_tags', 'pt', '')
			->joinLeft('tags', 't', 'pt.tag_id = t.id', Tags::model()->formatFields($fields))
			->where(array(
				'pt.post_id = ?'=>$post_id,
			))
			->order('t.`count`')
			->fetchAll();
	}
	
	/**
	 * 批量获取文章对应tags
	 * @param array $post_ids 文章ID构成的二维数组
	 * @param string $fields 标签字段，tags表字段
	 * @return array 返回以文章ID为key的三维数组
	 */
	public function mget($post_ids, $fields = 'id,title'){
		$sql = new Sql();
		$tags = $sql->from('posts_tags', 'pt', 'post_id')
			->joinLeft('tags', 't', 'pt.tag_id = t.id', Tags::model()->formatFields($fields))
			->where(array('pt.post_id IN (?)'=>$post_ids))
			->fetchAll();
		$return = array_fill_keys($post_ids, array());
		foreach($tags as $t){
			$p = $t['post_id'];
			unset($t['post_id']);
			$return[$p][] = $t;
		}
		return $return;
	}
}