<?php
namespace fayfeed\services;

use fay\core\ErrorException;
use fay\core\Service;
use fay\core\Sql;
use fay\helpers\FieldHelper;
use fay\models\tables\FeedsTable;
use fay\models\tables\FeedsFilesTable;
use fay\models\tables\UserCounterTable;
use fay\models\tables\FeedMetaTable;
use fay\helpers\RequestHelper;
use fay\models\tables\FeedExtraTable;
use fay\models\tables\FeedsTagsTable;
use fay\models\tables\FeedLikesTable;
use fay\models\tables\FeedFavoritesTable;
use fay\services\user\UserService;

/**
 * 动态服务
 */
class FeedService extends Service{
	/**
	 * 允许在接口调用时返回的字段
	 */
	public static $public_fields = array(
		'feed'=>array(
			'id', 'content', 'publish_time', 'address'
		),
		'category'=>array(
			'id', 'title', 'alias',
		),
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
		'tags'=>array(
			'id', 'title',
		),
		'files'=>array(
			'file_id', 'description',
		),
		'meta'=>array(
			'comments', 'likes', 'favorites'
		),
	);
	
	/**
	 * 默认接口返回字段
	 */
	public static $default_fields = array(
		'feed'=>array(
			'id', 'content', 'publish_time', 'address'
		),
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
		'files'=>array(
			'file_id', 'description',
		),
		'meta'=>array(
			'comments', 'likes', 'favorites'
		),
		'tags'=>array(
			'id', 'title',
		),
	);

	/**
	 * @param string $class_name
	 * @return FeedService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 创建一篇动态
	 * @param array $feed feeds表相关字段
	 * @param array $extra 其它字段
	 *   - tags 标签文本，逗号分割或一维数组
	 *   - files 由文件ID为键，文件描述为值构成的关联数组
	 *   - extra feed_extra相关字段
	 * @param int $user_id 用户ID
	 * @return int 动态ID
	 */
	public function create($feed, $extra = array(), $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		
		//动态表
		$feed['user_id'] = $user_id;
		$feed['create_time'] = \F::app()->current_time;
		$feed['update_time'] = \F::app()->current_time;
		empty($feed['publish_time']) && $feed['publish_time'] = \F::app()->current_time;//发布时间默认为当前时间
		$feed['publish_date'] = date('Y-m-d', $feed['publish_time']);
		empty($feed['sort']) && $feed['sort'] = \F::app()->current_time;//排序值默认为当前时间
		
		$feed_id = FeedsTable::model()->insert($feed, true);
		
		//计数表
		$feed_meta = array(
			'feed_id'=>$feed_id,
		);
		if(isset($extra['meta'])){
			$feed_meta = $feed_meta + $extra['meta'];
		}
		FeedMetaTable::model()->insert($feed_meta, true);
		
		//扩展表
		$feed_extra = array(
			'feed_id'=>$feed_id,
			'ip_int'=>RequestHelper::ip2int(\F::app()->ip),
		);
		if(isset($extra['extra'])){
			$feed_extra = $feed_extra + $extra['extra'];
		}
		FeedExtraTable::model()->insert($feed_extra, true);
		
		//标签
		if(isset($extra['tags'])){
			FeedTagService::service()->set($extra['tags'], $feed_id);
		}
		
		//附件
		if(isset($extra['files'])){
			$i = 0;
			foreach($extra['files'] as $file_id => $description){
				$i++;
				FeedsFilesTable::model()->insert(array(
					'file_id'=>$file_id,
					'feed_id'=>$feed_id,
					'description'=>$description,
					'sort'=>$i,
				));
			}
		}
		
		if(isset($feed['status'])){
			//如果有传入动态状态，且动态状态不是“草稿”，用户动态数加一
			if($feed['status'] != FeedsTable::STATUS_DRAFT){
				//用户动态数加一
				UserCounterTable::model()->incr($user_id, 'feeds', 1);
				
				//相关标签动态数加一
				FeedTagService::service()->incr($feed_id);
			}
		}else{
			//如果未传入status，获取动态状态进行判断
			$feed = FeedsTable::model()->find($feed_id, 'status');
			if($feed['status'] != FeedsTable::STATUS_DRAFT){
				//用户动态数加一
				UserCounterTable::model()->incr($user_id, 'feeds', 1);
				
				//相关标签动态数加一
				FeedTagService::service()->incr($feed_id);
			}
		}
		
		//触发事件
		\F::event()->trigger('after_feed_created', array(
			'feed_id'=>$feed_id,
		));
		
		return $feed_id;
	}
	
	/**
	 * 更新一篇动态
	 * @param int $feed_id 动态ID
	 * @param array $data feeds表相关字段
	 * @param array $extra 其它字段
	 *   - categories 附加分类ID，逗号分隔或一维数组。若不传，则不会更新，若传了空数组，则清空附加分类。
	 *   - tags 标签文本，逗号分割或一维数组。若不传，则不会更新，若传了空数组，则清空标签。
	 *   - files 由文件ID为键，文件描述为值构成的关联数组。若不传，则不会更新，若传了空数组，则清空附件。
	 *   - extra feed_extra相关字段
	 * @param bool $update_update_time 是否更新“最后更新时间”。默认为true
	 * @return bool
	 */
	public function update($feed_id, $data, $extra = array(), $update_update_time = true){
		//获取原动态
		$old_feed = FeedsTable::model()->find($feed_id, 'user_id,delete_time,status');
		if(!$old_feed){
			return false;
		}
		
		if($update_update_time){
			$data['update_time'] = \F::app()->current_time;
		}else if(isset($data['update_time'])){
			unset($data['update_time']);
		}
		
		//过滤掉多余的数据
		FeedsTable::model()->update($data, $feed_id, true);
		
		//若原动态未删除，更新用户及标签的动态数
		if(!$old_feed['delete_time']){
			if($old_feed['status'] == FeedsTable::STATUS_DRAFT &&
				isset($data['status']) && $data['status'] != FeedsTable::STATUS_DRAFT){
				//若原动态是“草稿”状态，且新状态不是“草稿”
				UserCounterTable::model()->incr($old_feed['user_id'], 'feeds', 1);
				
				//相关标签动态数减一
				FeedTagService::service()->decr($feed_id);
			}else if($old_feed['status'] != FeedsTable::STATUS_DRAFT &&
				isset($data['status']) && $data['status'] == FeedsTable::STATUS_DRAFT){
				//若原动态不是“草稿”状态，且新状态是“草稿”
				UserCounterTable::model()->incr($old_feed['user_id'], 'feeds', -1);
				
				//相关标签动态数加一
				FeedTagService::service()->incr($feed_id);
			}
		}
		
		//计数表
		if(!empty($extra['meta'])){
			FeedMetaTable::model()->update($extra['meta'], $feed_id, true);
		}
		
		//扩展表
		if(!empty($extra['extra'])){
			FeedExtraTable::model()->update($extra['extra'], $feed_id, true);
		}
		
		//标签
		if(isset($extra['tags'])){
			FeedTagService::service()->set($extra['tags'], $feed_id);
		}
		
		//附件
		if(isset($extra['files'])){
			//删除已被删除的图片
			if($extra['files']){
				FeedsFilesTable::model()->delete(array(
					'feed_id = ?'=>$feed_id,
					'file_id NOT IN (?)'=>array_keys($extra['files']),
				));
			}else{
				FeedsFilesTable::model()->delete(array(
					'feed_id = ?'=>$feed_id,
				));
			}
			//获取已存在的图片
			$old_files_ids = FeedsFilesTable::model()->fetchCol('file_id', array(
				'feed_id = ?'=>$feed_id,
			));
			$i = 0;
			foreach($extra['files'] as $file_id => $description){
				$i++;
				if(in_array($file_id, $old_files_ids)){
					FeedsFilesTable::model()->update(array(
						'description'=>$description,
						'sort'=>$i,
					), array(
						'feed_id = ?'=>$feed_id,
						'file_id = ?'=>$file_id,
					));
				}else{
					FeedsFilesTable::model()->insert(array(
						'feed_id'=>$feed_id,
						'file_id'=>$file_id,
						'description'=>$description,
						'sort'=>$i,
					));
				}
			}
		}
		
		//触发事件
		\F::event()->trigger('after_feed_updated', array(
			'feed_id'=>$feed_id,
		));
		
		return true;
	}
	
	/**
	 * 删除一篇动态
	 * @param int $feed_id 动态ID
	 * @return bool
	 */
	public function delete($feed_id){
		$feed = FeedsTable::model()->find($feed_id, 'user_id,delete_time,status');
		if(!$feed || $feed['delete_time']){
			return false;
		}
		
		//标记为已删除
		FeedsTable::model()->update(array(
			'delete_time'=>\F::app()->current_time,
		), $feed_id);
		
		//若被删除动态不是“草稿”
		if($feed['status'] != FeedsTable::STATUS_DRAFT){
			//用户动态数减一
			UserCounterTable::model()->incr($feed['user_id'], 'feeds', -1);
			
			//相关标签动态数减一
			FeedTagService::service()->decr($feed_id);
		}
		
		//触发事件
		\F::event()->trigger('after_feed_deleted', array(
			'feed_id'=>$feed_id,
		));
		
		return true;
	}
	
	/**
	 * 还原一篇动态
	 * @param int $feed_id 动态ID
	 * @return bool
	 */
	public function undelete($feed_id){
		$feed = FeedsTable::model()->find($feed_id, 'user_id,delete_time');
		if(!$feed || !$feed['delete_time']){
			return false;
		}
		
		//标记为未删除
		FeedsTable::model()->update(array(
			'delete_time'=>0
		), $feed_id);
		
		//若被还原动态不是“草稿”
		if($feed['status'] != FeedsTable::STATUS_DRAFT){
			//用户动态数减一
			UserCounterTable::model()->incr($feed['user_id'], 'feeds', 1);
			
			//相关标签动态数加一
			FeedTagService::service()->incr($feed_id);
		}
		
		//触发事件
		\F::event()->trigger('after_feed_undeleted', array(
			'feed_id'=>$feed_id,
		));
		
		return true;
	}
	
	/**
	 * 彻底删除一篇动态
	 * @param $feed_id
	 * @return bool
	 */
	public function remove($feed_id){
		//获取动态删除状态
		$feed = FeedsTable::model()->find($feed_id, 'user_id,delete_time,status');
		if(!$feed){
			return false;
		}
		
		//触发事件
		\F::event()->trigger('before_feed_removed', array(
			'feed_id'=>$feed_id,
		));
		
		//删除动态
		FeedsTable::model()->delete($feed_id);
		
		//若动态未通过回收站被直接删除，且不是“草稿”
		if(!$feed['delete_time'] && $feed['status'] != FeedsTable::STATUS_DRAFT){
			//则作者动态数减一
			UserCounterTable::model()->incr($feed['user_id'], 'feed', -1);
			
			//相关标签动态数减一
			FeedTagService::service()->decr($feed_id);
		}
		//删除动态与标签的关联关系
		FeedsTagsTable::model()->delete('feed_id = ' . $feed_id);
		
		//删除动态附件（只是删除对应关系，并不删除附件文件）
		FeedsFilesTable::model()->delete('feed_id = '.$feed_id);
		
		//删除关注，收藏列表
		FeedLikesTable::model()->delete('feed_id = '.$feed_id);
		FeedFavoritesTable::model()->delete('feed_id = '.$feed_id);
		
		//删除动态meta信息
		FeedMetaTable::model()->delete('feed_id = ' . $feed_id);
		
		return true;
	}
	
	/**
	 * 判断一个动态ID是否存在（“已删除/未发布/未到定时发布时间”的动态都被视为不存在）
	 * @param int $feed_id
	 * @return bool 若动态已发布且未删除返回true，否则返回false
	 */
	public static function isFeedIdExist($feed_id){
		if($feed_id){
			$feed = FeedsTable::model()->find($feed_id, 'delete_time,publish_time,status');
			if($feed['delete_time'] || $feed['publish_time'] > \F::app()->current_time || $feed['status'] == FeedsTable::STATUS_DRAFT){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 返回一篇动态
	 * @param int $id 动态ID
	 * @param string $fields 可指定返回字段
	 *  - feeds.*系列可指定feeds表返回字段，若有一项为'feed.*'，则返回所有字段
	 *  - meta.*系列可指定feed_meta表返回字段，若有一项为'meta.*'，则返回所有字段
	 *  - tags.*系列可指定标签相关字段，可选tags表字段，若有一项为'tags.*'，则返回所有字段
	 *  - files.*系列可指定feeds_files表返回字段，若有一项为'feeds_files.*'，则返回所有字段
	 *  - props.*系列可指定返回哪些动态分类属性，若有一项为'props.*'，则返回所有动态分类属性
	 *  - user.*系列可指定作者信息，格式参照\fay\services\user\UserService::get()
	 * @param bool $only_published 若为true，则只在已发布的动态里搜索。默认为true
	 * @return array|bool
	 */
	public function get($id, $fields = null, $only_published = true){
		$fields || $fields = self::$default_fields;
		//解析$fields
		$fields = FieldHelper::parse($fields, 'feed');
		if(empty($fields['feed']) || in_array('*', $fields['feed'])){
			//若未指定返回字段，初始化（默认不返回content，因为列表页基本是不会显示动态详情的）
			$fields['feed'] = FeedsTable::model()->getFields();
		}
		
		$feed_fields = $fields['feed'];
		if(!empty($fields['user']) && !in_array('user_id', $feed_fields)){
			//如果要获取作者信息，则必须搜出user_id
			$feed_fields[] = 'user_id';
		}
		
		$sql = new Sql();
		$sql->from(array('f'=>FeedsTable::model()->getTableName()), $feed_fields)
			->where('id = ?', $id);
		
		//仅搜索已发布的动态
		if($only_published){
			$sql->where(array(
				'f.delete_time = 0',
				'f.status != '.FeedsTable::STATUS_DRAFT,
				'f.publish_time < '.\F::app()->current_time,
			));
		}
		
		$feed = $sql->fetchRow();
		if(!$feed){
			return false;
		}
		
		$return = array(
			'feed'=>$feed,
		);
		
		//meta
		if(!empty($fields['meta'])){
			$return['meta'] = FeedMetaService::service()->get($id, $fields['meta']);
		}
		
		//作者信息
		if(!empty($fields['user'])){
			$return['user'] = UserService::service()->get($feed['user_id'], $fields['user']);
		}
		
		//标签
		if(!empty($fields['tags'])){
			$return['tags'] = FeedTagService::service()->get($id, $fields['tags']);
		}
		
		//附件
		if(!empty($fields['files'])){
			$return['files'] = FeedFileService::service()->get($id, $fields['files']);
		}
		
		return $return;
	}
	
	/**
	 *
	 * @param array $feed_ids 动态ID构成的一维数组
	 * @param string|array $fields
	 *  - feeds.*系列可指定feeds表返回字段，若有一项为'feed.*'，则返回所有字段
	 *  - meta.*系列可指定feed_meta表返回字段，若有一项为'meta.*'，则返回所有字段
	 *  - tags.*系列可指定标签相关字段，可选tags表字段，若有一项为'tags.*'，则返回所有字段
	 *  - files.*系列可指定feeds_files表返回字段，若有一项为'feeds_files.*'，则返回所有字段
	 *  - props.*系列可指定返回哪些动态分类属性，若有一项为'props.*'，则返回所有动态分类属性
	 *  - user.*系列可指定作者信息，格式参照\fay\services\user\UserService::get()
	 * @param bool $only_published 若为true，则只在已发布的动态里搜索。默认为true
	 * @return array
	 */
	public function mget($feed_ids, $fields, $only_published = true){
		//解析$fields
		$fields = FieldHelper::parse($fields, 'feed');
		if(empty($fields['feed']) || in_array('*', $fields['feed'])){
			//若未指定返回字段，初始化（默认不返回content，因为列表页基本是不会显示动态详情的）
			$fields['feed'] = FeedsTable::model()->getFields();
		}
		
		$feed_fields = $fields['feed'];
		if(!empty($fields['user']) && !in_array('user_id', $feed_fields)){
			//如果要获取作者信息，则必须搜出user_id
			$feed_fields[] = 'user_id';
		}
		if(!in_array('id', $fields['feed'])){
			//id字段无论如何都要返回，因为后面要用到
			$feed_fields[] = 'id';
		}
		
		$sql = new Sql();
		$sql->from(array('p'=>FeedsTable::model()->getTableName()), $feed_fields)
			->where('id IN (?)', $feed_ids);
		
		//仅搜索已发布的动态
		if($only_published){
			$sql->where(array(
				'p.delete_time = 0',
				'p.status != '.FeedsTable::STATUS_DRAFT,
				'p.publish_time < '.\F::app()->current_time,
			));
		}
		
		$feeds = $sql->fetchAll();
		
		if(!$feeds){
			return array();
		}
		
		//meta
		if(!empty($fields['meta'])){
			$feed_metas = FeedMetaService::service()->mget($feed_ids, $fields['meta']);
		}
		
		//标签
		if(!empty($fields['tags'])){
			$feed_tags = FeedTagService::service()->mget($feed_ids, $fields['tags']);
		}
		
		//附件
		if(!empty($fields['files'])){
			$feed_files = FeedFileService::service()->mget($feed_ids, $fields['files']);
		}
	}
	
	/**
	 * 判断指定用户是否具备对指定动态的删除权限
	 * @param int|array $feed 动态
	 *  - 若是数组，视为动态表行记录，必须包含user_id字段
	 *  - 若是数字，视为动态ID，会根据ID搜索数据库
	 * @param string $user_id 用户ID，若为空，则默认为当前登录用户
	 * @return bool
	 * @throws ErrorException
	 */
	public function checkDeletePermission($feed, $user_id = null){
		if(!is_array($feed)){
			$feed = FeedsTable::model()->find($feed, 'user_id');
		}
		$user_id || $user_id = \F::app()->current_user;
		
		if(empty($feed['user_id'])){
			throw new ErrorException('指定动态不存在');
		}
		
		if($feed['user_id'] == $user_id){
			//自己的动态总是有权限删除的
			return true;
		}
		
		if(UserService::service()->isAdmin($user_id)){
			if(UserService::service()->checkPermission('admin/feed/delete', $user_id)){
				return true;
			}
		}
		
		return false;
	}
}