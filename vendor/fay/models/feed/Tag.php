<?php
namespace fay\models\feed;

use fay\core\Model;
use fay\core\Sql;
use fay\models\tables\Tags;

class Tag extends Model{
	/**
	 * 默认返回字段
	 */
	private $default_fields = array('id', 'title');
	
	/**
	 * @return Tag
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取动态对应tags
	 * @param int $feed_id 动态ID
	 * @param string $fields 标签字段，tags表字段
	 * @return array 返回包含动态tag信息的二维数组
	 */
	public function get($feed_id, $fields = null){
		if(empty($fields) || empty($fields[0])){
			//若传入$fields为空，则返回默认字段
			$fields = $this->default_fields;
		}
		$sql = new Sql();
		return $sql->from(array('ft'=>'feeds_tags'), '')
			->joinLeft(array('t'=>'tags'), 'ft.tag_id = t.id', Tags::model()->formatFields($fields))
			->where(array(
				'ft.feed_id = ?'=>$feed_id,
			))
			->fetchAll();
	}
	
	/**
	 * 批量获取动态对应tags
	 * @param array $feed_ids 动态ID构成的二维数组
	 * @param string $fields 标签字段，tags表字段
	 * @return array 返回以动态ID为key的三维数组
	 */
	public function mget($feed_ids, $fields = null){
		if(empty($fields) || empty($fields[0])){
			//若传入$fields为空，则返回默认字段
			$fields = $this->default_fields;
		}
		$sql = new Sql();
		$tags = $sql->from(array('ft'=>'feeds_tags'), 'feed_id')
			->joinLeft(array('t'=>'tags'), 'ft.tag_id = t.id', Tags::model()->formatFields($fields))
			->where(array('ft.feed_id IN (?)'=>$feed_ids))
			->fetchAll();
		$return = array_fill_keys($feed_ids, array());
		foreach($tags as $t){
			$p = $t['feed_id'];
			unset($t['feed_id']);
			$return[$p][] = $t;
		}
		return $return;
	}
}