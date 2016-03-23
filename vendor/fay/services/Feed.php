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

/**
 * 动态
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
		$feed['publish_time'] || $feed['publish_time'] = \F::app()->current_time;
		$feed['publish_date'] = date('Y-m-d', $feed['publish_time']);
		
		$feed_id = Feeds::model()->insert($feed, true);
		
		//动态计数表
		$feed_meta = array(
			'feed_id'=>$feed_id,
		);
		if(isset($extra['meta'])){
			$feed_meta = $feed_meta + $extra['extra'];
		}
		FeedMeta::model()->insert($feed_meta, true);
		
		//动态扩展表
		$feed_extra = array(
			'feed_id'=>$feed_id,
			'ip_int'=>Request::ip2int(\F::app()->ip),
		);
		if(isset($extra['extra'])){
			$feed_extra = $feed_extra + $extra['extra'];
		}
		FeedExtra::model()->insert($feed_extra, true);
		
		//标签
		if(isset($extra['tags'])){
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
		
		//用户动态数加一
		UserCounter::model()->incr($user_id, 'feeds', 1);
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
	 */
	public function update($feed_id, $data, $extra = array()){
		$data['last_modified_time'] = \F::app()->current_time;
		//过滤掉多余的数据
		Feeds::model()->update($data, $feed_id, true);
		
		$feed_meta = FeedMeta::model()->fillData($data, false);
		if($feed_meta){
			FeedMeta::model()->update($feed_meta, $feed_id);
		}
		
		
	}
}