<?php
namespace fay\services\feed;

use fay\core\Model;
use fay\models\tables\Tags;
use fay\models\tables\FeedsTags;
use fay\models\feed\Tag as FeedTag;
use fay\services\Tag as TagService;
use fay\models\Tag as TagModel;

class Tag extends Model{
	/**
	 * @return Tag
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
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
				$tag = Tags::model()->fetchRow(array(
					'title = ?'=>$tag_title,
				), 'id');
				if($tag){//已存在，获取id
					$input_tag_ids[] = $tag['id'];
				}else{//不存在，插入新tag
					$input_tag_ids[] = TagService::model()->create($tag_title);
				}
			}
			
			$old_tag_ids = FeedsTags::model()->fetchCol('tag_id', array(
				'feed_id = ?'=>$feed_id,
			));
			
			//删除已被删除的标签
			$deleted_tag_ids = array_diff($old_tag_ids, $input_tag_ids);
			if($deleted_tag_ids){
				FeedsTags::model()->delete(array(
					'feed_id = ?'=>$feed_id,
					'tag_id IN (?)'=>$deleted_tag_ids
				));
			}
			
			//插入新的标签
			$new_tag_ids = array_diff($input_tag_ids, $old_tag_ids);
			if($new_tag_ids){
				foreach($new_tag_ids as $v){
					FeedsTags::model()->insert(array(
						'feed_id'=>$feed_id,
						'tag_id'=>$v,
					));
				}
			}
			
			//更新标签对应的动态数
			if($new_tag_ids){
				TagModel::model()->incr($new_tag_ids, 'feeds');
			}
			if($deleted_tag_ids){
				TagModel::model()->decr($deleted_tag_ids, 'feeds');
			}
		}else{
			//删除全部tag
			$old_tag_ids = FeedsTags::model()->fetchCol('tag_id', array(
				'feed_id = ?'=>$feed_id,
			));
			if($old_tag_ids){
				FeedsTags::model()->delete(array(
					'feed_id = ?'=>$feed_id,
				));
				if($old_tag_ids){
					FeedTag::model()->decr($old_tag_ids, 'feeds');
				}
			}
		}
	}
}