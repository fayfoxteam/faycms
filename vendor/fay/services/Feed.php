<?php
namespace fay\services;

use fay\core\Model;
use fay\models\tables\Feeds;
use fay\services\feed\Tag as FeedTagService;
use fay\models\tables\FeedsFiles;
use fay\models\tables\UserCounter;
use fay\models\tables\FeedMeta;
use fay\helpers\Request;
use fay\models\tables\FeedExtra;
use fay\core\Hook;
use fay\models\feed\Tag as FeedTagModel;
use fay\models\tables\FeedsTags;
use fay\models\tables\FeedLikes;
use fay\models\tables\FeedFavorites;

/**
 * 动态服务
 */
class Feed extends Model{

	/**
	 * @return Feed
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 创建一篇动态
	 * @param array $feed feeds表相关字段
	 * @param array $extra 其它字段
	 *   - tags 标签文本，逗号分割或一维数组
	 *   - files 由文件ID为键，文件描述为值构成的关联数组
	 *   - extra feed_extra相关字段
	 * @param int $user_id 用户ID
	 */
	public function create($feed, $extra = array(), $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		
		//动态表
		$feed['user_id'] = $user_id;
		$feed['create_time'] = \F::app()->current_time;
		$feed['last_modified_time'] = \F::app()->current_time;
		empty($feed['publish_time']) && $feed['publish_time'] = \F::app()->current_time;//发布时间默认为当前时间
		$feed['publish_date'] = date('Y-m-d', $feed['publish_time']);
		empty($feed['sort']) && $feed['sort'] = \F::app()->current_time;//排序值默认为当前时间
		
		$feed_id = Feeds::model()->insert($feed, true, array('id'));
		
		//计数表
		$feed_meta = array(
			'feed_id'=>$feed_id,
		);
		if(isset($extra['meta'])){
			$feed_meta = $feed_meta + $extra['meta'];
		}
		FeedMeta::model()->insert($feed_meta, true);
		
		//扩展表
		$feed_extra = array(
			'feed_id'=>$feed_id,
			'ip_int'=>Request::ip2int(\F::app()->ip),
		);
		if(isset($extra['extra'])){
			$feed_extra = $feed_extra + $extra['extra'];
		}
		FeedExtra::model()->insert($feed_extra, true);
		
		//标签
		if($extra['tags']){
			FeedTagService::model()->set($extra['tags'], $feed_id);
		}
		
		//附件
		if($extra['files']){
			$i = 0;
			foreach($extra['files'] as $file_id => $description){
				$i++;
				FeedsFiles::model()->insert(array(
					'file_id'=>$file_id,
					'feed_id'=>$feed_id,
					'description'=>$description,
					'sort'=>$i,
				));
			}
		}
		
		if(isset($feed['status'])){
			//如果有传入动态状态，且动态状态不是“草稿”，用户动态数加一
			if($feed['status'] != Feeds::STATUS_DRAFT){
				//用户动态数加一
				UserCounter::model()->incr($user_id, 'feeds', 1);
				
				//相关标签动态数加一
				FeedTagModel::model()->incr($feed_id);
			}
		}else{
			//如果未传入status，获取动态状态进行判断
			$feed = Feeds::model()->find($feed_id, 'status');
			if($feed['status'] != Feeds::STATUS_DRAFT){
				//用户动态数加一
				UserCounter::model()->incr($user_id, 'feeds', 1);
				
				//相关标签动态数加一
				FeedTagModel::model()->incr($feed_id);
			}
		}
		
		//hook
		Hook::getInstance()->call('after_feed_created', array(
			'feed_id'=>$feed_id,
		));
		
		return $feed_id;
	}
	
	/**
	 * 更新一篇动态
	 * @param int $feed_id 动态ID
	 * @param array $feed feeds表相关字段
	 * @param array $extra 其它字段
	 *   - categories 附加分类ID，逗号分隔或一维数组。若不传，则不会更新，若传了空数组，则清空附加分类。
	 *   - tags 标签文本，逗号分割或一维数组。若不传，则不会更新，若传了空数组，则清空标签。
	 *   - files 由文件ID为键，文件描述为值构成的关联数组。若不传，则不会更新，若传了空数组，则清空附件。
	 *   - extra feed_extra相关字段
	 * @param bool $update_last_modified_time 是否更新“最后更新时间”。默认为true
	 */
	public function update($feed_id, $data, $extra = array(), $update_last_modified_time = true){
		//获取原动态
		$old_feed = Feeds::model()->find($feed_id, 'user_id,deleted,status');
		if(!$old_feed){
			return false;
		}
		
		if($update_last_modified_time){
			$data['last_modified_time'] = \F::app()->current_time;
		}else if(isset($data['last_modified_time'])){
			unset($data['last_modified_time']);
		}
		
		//过滤掉多余的数据
		Feeds::model()->update($data, $feed_id, true, array('id', 'create_time', 'deleted'));
		
		//若原动态未删除，更新用户及标签的动态数
		if(!$old_feed['deleted']){
			if($old_feed['status'] == Feeds::STATUS_DRAFT &&
				isset($data['status']) && $data['status'] != Feeds::STATUS_DRAFT){
				//若原动态是“草稿”状态，且新状态不是“草稿”
				UserCounter::model()->incr($old_feed['user_id'], 'feeds', 1);
				
				//相关标签动态数减一
				FeedTagModel::model()->decr($feed_id);
			}else if($old_feed['status'] != Feeds::STATUS_DRAFT &&
				isset($data['status']) && $data['status'] == Feeds::STATUS_DRAFT){
				//若原动态不是“草稿”状态，且新状态是“草稿”
				UserCounter::model()->incr($old_feed['user_id'], 'feeds', -1);
				
				//相关标签动态数加一
				FeedTagModel::model()->incr($feed_id);
			}
		}
		
		//计数表
		if(!empty($extra['meta'])){
			FeedMeta::model()->update($extra['meta'], $feed_id, true);
		}
		
		//扩展表
		if(!empty($extra['extra'])){
			FeedExtra::model()->update($extra['extra'], $feed_id, true);
		}
		
		//标签
		if(isset($extra['tags'])){
			FeedTagService::model()->set($extra['tags'], $feed_id);
		}
		
		//附件
		if(isset($extra['files'])){
			//删除已被删除的图片
			if($extra['files']){
				FeedsFiles::model()->delete(array(
					'feed_id = ?'=>$feed_id,
					'file_id NOT IN (?)'=>array_keys($extra['files']),
				));
			}else{
				FeedsFiles::model()->delete(array(
					'feed_id = ?'=>$feed_id,
				));
			}
			//获取已存在的图片
			$old_files_ids = FeedsFiles::model()->fetchCol('file_id', array(
				'feed_id = ?'=>$feed_id,
			));
			$i = 0;
			foreach($extra['files'] as $file_id => $description){
				$i++;
				if(in_array($file_id, $old_files_ids)){
					FeedsFiles::model()->update(array(
						'description'=>$description,
						'sort'=>$i,
					), array(
						'feed_id = ?'=>$feed_id,
						'file_id = ?'=>$file_id,
					));
				}else{
					FeedsFiles::model()->insert(array(
						'feed_id'=>$feed_id,
						'file_id'=>$file_id,
						'description'=>$description,
						'sort'=>$i,
					));
				}
			}
		}
		
		//hook
		Hook::getInstance()->call('after_feed_updated', array(
			'feed_id'=>$feed_id,
		));
		
		return true;
	}
	
	/**
	 * 删除一篇动态
	 * @param int $feed_id 动态ID
	 */
	public function delete($feed_id){
		$feed = Feeds::model()->find($feed_id, 'user_id,deleted,status');
		if(!$feed || $feed['deleted']){
			return false;
		}
		
		//标记为已删除
		Feeds::model()->update(array(
			'deleted'=>1
		), $feed_id);
		
		//若被删除动态不是“草稿”
		if($feed['status'] != Feeds::STATUS_DRAFT){
			//用户动态数减一
			UserCounter::model()->incr($feed['user_id'], 'feeds', -1);
			
			//相关标签动态数减一
			FeedTagModel::model()->decr($feed_id);
		}
		
		//执行钩子
		Hook::getInstance()->call('after_feed_deleted', array(
			'feed_id'=>$feed_id,
		));
		
		return true;
	}
	
	/**
	 * 还原一篇动态
	 * @param int $feed_id 动态ID
	 */
	public function undelete($feed_id){
		$feed = Feeds::model()->find($feed_id, 'user_id,deleted');
		if(!$feed || !$feed['deleted']){
			return false;
		}
		
		//标记为未删除
		Feeds::model()->update(array(
			'deleted'=>0
		), $feed_id);
		
		//若被还原动态不是“草稿”
		if($feed['status'] != Feeds::STATUS_DRAFT){
			//用户动态数减一
			UserCounter::model()->incr($feed['user_id'], 'feeds', 1);
			
			//相关标签动态数加一
			FeedTagModel::model()->incr($feed_id);
		}
		
		//执行钩子
		Hook::getInstance()->call('after_feed_undeleted', array(
			'feed_id'=>$feed_id,
		));
		
		return true;
	}
	
	/**
	 * 彻底删除一篇动态
	 */
	public function remove($feed_id){
		//获取动态删除状态
		$feed = Feeds::model()->find($feed_id, 'user_id,deleted,status');
		if(!$feed){
			return false;
		}
		
		//执行钩子
		Hook::getInstance()->call('before_feed_removed', array(
			'feed_id'=>$feed_id,
		));
		
		//删除动态
		Feeds::model()->delete($feed_id);
		
		//若动态未通过回收站被直接删除，且不是“草稿”
		if(!$feed['deleted'] && $feed['status'] != Feeds::STATUS_DRAFT){
			//则作者动态数减一
			UserCounter::model()->incr($feed['user_id'], 'feed', -1);
			
			//相关标签动态数减一
			FeedTagModel::model()->decr($feed_id);
		}
		//删除动态与标签的关联关系
		FeedsTags::model()->delete('feed_id = ' . $feed_id);
		
		//删除动态附件（只是删除对应关系，并不删除附件文件）
		FeedsFiles::model()->delete('feed_id = '.$feed_id);
		
		//删除关注，收藏列表
		FeedLikes::model()->delete('feed_id = '.$feed_id);
		FeedFavorites::model()->delete('feed_id = '.$feed_id);
		
		//删除动态meta信息
		FeedMeta::model()->delete('feed_id = ' . $feed_id);
		
		return true;
	}
}