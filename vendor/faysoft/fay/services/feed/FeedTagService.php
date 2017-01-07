<?php
namespace fay\services\feed;

use fay\core\Service;
use fay\core\Sql;
use fay\helpers\ArrayHelper;
use fay\models\tables\FeedsTable;
use fay\models\tables\TagCounterTable;
use fay\models\tables\TagsTable;
use fay\models\tables\FeedsTagsTable;
use fay\services\TagService;
use fay\services\tag\TagCounterService;

class FeedTagService extends Service{
	/**
	 * 默认返回字段
	 */
	public static $default_fields = array('id', 'title');
	
	/**
	 * @param string $class_name
	 * @return FeedTagService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 获取动态对应tags
	 * @param int $feed_id 动态ID
	 * @param string $fields 标签字段，tags表字段
	 * @return array 返回包含动态tag信息的二维数组
	 */
	public function get($feed_id, $fields = null){
		if(empty($fields)){
			//若传入$fields为空，则返回默认字段
			$fields = self::$default_fields;
		}
		$sql = new Sql();
		return $sql->from(array('ft'=>'feeds_tags'), '')
			->joinLeft(array('t'=>'tags'), 'ft.tag_id = t.id', TagsTable::model()->formatFields($fields))
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
		if(empty($fields)){
			//若传入$fields为空，则返回默认字段
			$fields = self::$default_fields;
		}
		$sql = new Sql();
		$tags = $sql->from(array('ft'=>'feeds_tags'), 'feed_id')
			->joinLeft(array('t'=>'tags'), 'ft.tag_id = t.id', TagsTable::model()->formatFields($fields))
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
		return FeedsTagsTable::model()->fetchCol('tag_id', array(
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
					'p.status = '.FeedsTable::STATUS_APPROVED,
				))//这里不限制publish_time条件，因为定时发布后没逻辑来更新标签对应动态数
				->group('pt.tag_id')
				->fetchAll();
			$feeds_tags = ArrayHelper::column($feeds_tags, 'count', 'tag_id');
			foreach($tag_ids as $tag){
				TagCounterTable::model()->update(array(
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
		
		$tag_ids = FeedsTagsTable::model()->fetchCol('tag_id', array(
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
			TagCounterService::service()->incr($tag_ids, 'feeds');
		}
	}
	
	/**
	 * 递减一篇动态相关的标签动态数
	 * @param int $feed_id 动态ID
	 */
	public function decr($feed_id){
		$tag_ids = $this->getTagIds($feed_id);
		if($tag_ids){
			TagCounterService::service()->decr($tag_ids, 'feeds');
		}
	}
	
	/**
	 * 设置一篇动态的标签
	 * @param string|array $tags 逗号分割的标签文本，或由标签文本构成的一维数组。若为空，则删除指定动态的所有标签
	 * @param int $feed_id
	 */
	public function set($tags, $feed_id){
		if($tags){
			if(!is_array($tags)){
				$tags = explode(',', $tags);
			}
			$input_tag_ids = array();
			foreach($tags as $tag_title){
				if(!$tag_title = trim($tag_title))continue;
				$tag = TagsTable::model()->fetchRow(array(
					'title = ?'=>$tag_title,
				), 'id');
				if($tag){//已存在，获取id
					$input_tag_ids[] = $tag['id'];
				}else{//不存在，插入新tag
					$input_tag_ids[] = TagService::service()->create($tag_title);
				}
			}
			
			$old_tag_ids = FeedsTagsTable::model()->fetchCol('tag_id', array(
				'feed_id = ?'=>$feed_id,
			));
			
			//删除已被删除的标签
			$deleted_tag_ids = array_diff($old_tag_ids, $input_tag_ids);
			if($deleted_tag_ids){
				FeedsTagsTable::model()->delete(array(
					'feed_id = ?'=>$feed_id,
					'tag_id IN (?)'=>$deleted_tag_ids
				));
			}
			
			//插入新的标签
			$new_tag_ids = array_diff($input_tag_ids, $old_tag_ids);
			if($new_tag_ids){
				foreach($new_tag_ids as $v){
					FeedsTagsTable::model()->insert(array(
						'feed_id'=>$feed_id,
						'tag_id'=>$v,
					));
				}
			}
			
			//更新标签对应的动态数
			if($new_tag_ids){
				TagCounterService::service()->incr($new_tag_ids, 'feeds');
			}
			if($deleted_tag_ids){
				TagCounterService::service()->decr($deleted_tag_ids, 'feeds');
			}
		}else{
			//删除全部tag
			$old_tag_ids = FeedsTagsTable::model()->fetchCol('tag_id', array(
				'feed_id = ?'=>$feed_id,
			));
			if($old_tag_ids){
				FeedsTagsTable::model()->delete(array(
					'feed_id = ?'=>$feed_id,
				));
				if($old_tag_ids){
					TagCounterService::service()->decr($old_tag_ids, 'feeds');
				}
			}
		}
	}
}