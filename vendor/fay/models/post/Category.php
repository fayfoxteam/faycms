<?php
namespace fay\models\post;

use fay\core\Model;
use fay\core\Sql;
use fay\models\tables\Categories;

class Category extends Model{
	/**
	 * 默认返回字段
	 */
	private $default_fields = array('id', 'title');
	
	/**
	 * @return Category
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取文章附加分类
	 * @param int $post_id 文章ID
	 * @param string $fields 分类字段（categories表字段）
	 * @return array 返回包含分类信息的二维数组
	 */
	public function get($post_id, $fields = null){
		if(empty($fields) || empty($fields[0])){
			//若传入$fields为空，则返回默认字段
			$fields = $this->default_fields;
		}
		
		$sql = new Sql();
		return $sql->from(array('pc'=>'posts_categories'), '')
			->joinLeft(array('c'=>'categories'), 'pc.cat_id = c.id', Categories::model()->formatFields($fields))
			->where(array('pc.post_id = ?'=>$post_id))
			->fetchAll();
	}
	
	/**
	 * 批量获取文章附加分类
	 * @param array $post_ids 文章ID构成的二维数组
	 * @param string $fields 分类字段（categories表字段）
	 * @return array 返回以文章ID为key的三维数组
	 */
	public function mget($post_ids, $fields = null){
		if(empty($fields) || empty($fields[0])){
			//若传入$fields为空，则返回默认字段
			$fields = $this->default_fields;
		}
		
		$sql = new Sql();
		$cats = $sql->from(array('pc'=>'posts_categories'), 'post_id')
			->joinLeft(array('c'=>'categories'), 'pc.cat_id = c.id', Categories::model()->formatFields($fields))
			->where(array('pc.post_id IN (?)'=>$post_ids))
			->fetchAll();
		$return = array_fill_keys($post_ids, array());
		foreach($cats as $c){
			$p = $c['post_id'];
			unset($c['post_id']);
			$return[$p][] = $c;
		}
		return $return;
	}
}