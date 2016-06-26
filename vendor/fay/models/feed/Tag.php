<?php
namespace fay\models\feed;

use fay\core\Model;
use fay\core\Sql;
use fay\models\tables\Tags;
use fay\services\Tag as TagService;
use fay\models\tables\FeedsTags;
use fay\helpers\ArrayHelper;
use fay\models\tables\TagCounter;
use fay\models\tables\Feeds;

class Tag extends Model{
	/**
	 * 默认返回字段
	 */
	private $default_fields = array('id', 'title');
	
	/**
	 * @param string $class_name
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
	
	/**
	 * 根据动态ID，返回动态对应标签的ID
	 * @param int $feed_id 动态ID
	 * @return array 标签ID构成的一维数组
	 */
	public function getTagIds($feed_id){
		return FeedsTags::model()->fetchCol('tag_id', array(
			'feed_id = ?'=>$feed_id,
		));
	}
	
	/**
	 * 根据tag_id刷新该tag的count种值
	 * @param int|array $tag_ids 可以传入单个ID或一维数组
	 */
	public function refreshCountByTagId($tag_ids){
		if(!is_array($tag_ids)){
			$tag_ids = array($tag_ids);
		}
		
		if($tag_ids){
			$sql = new Sql();
			$feeds_tags = $sql->from(array('pt'=>'feeds_tags'), 'COUNT(*) AS count, tag_id')
				->joinLeft(array('p'=>'feeds'), 'pt.feed_id = p.id')
				->where(array(
					'pt.tag_id IN (?)'=>$tag_ids,
					'p.deleted = 0',
					'p.status = '.Feeds::STATUS_APPROVED,
				))//这里不限制publish_time条件，因为定时发布后没逻辑来更新标签对应动态数
				->group('pt.tag_id')
				->fetchAll();
			$feeds_tags = ArrayHelper::column($feeds_tags, 'count', 'tag_id');
			foreach($tag_ids as $tag){
				TagCounter::model()->update(array(
					'feeds'=>empty($feeds_tags[$tag]) ? 0 : $feeds_tags[$tag],
				), $tag);
			}
		}
	}
	
	/**
	 * 根据动态ID，刷新该动态对应的tags的count值
	 * @param int|array $feed_id 可以传入单个ID或一维数组
	 */
	public function refreshCountByFeedId($feed_id){
		if(!is_array($feed_id)){
			$feed_id = array($feed_id);
		}
		
		$tag_ids = FeedsTags::model()->fetchCol('tag_id', array(
			'feed_id IN (?)'=>$feed_id,
		));
		
		$this->refreshCountByTagId($tag_ids);
	}
	
	/**
	 * 递增一篇动态相关的标签动态数
	 * @param int $feed_id 动态ID
	 */
	public function incr($feed_id){
		$tag_ids = $this->getTagIds($feed_id);
		if($tag_ids){
			TagService::service()->incr($tag_ids, 'feeds');
		}
	}
	
	/**
	 * 递减一篇动态相关的标签动态数
	 * @param int $feed_id 动态ID
	 */
	public function decr($feed_id){
		$tag_ids = $this->getTagIds($feed_id);
		if($tag_ids){
			TagService::service()->decr($tag_ids, 'feeds');
		}
	}
}