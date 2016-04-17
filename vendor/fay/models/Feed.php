<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Feeds;
use fay\helpers\FieldHelper;
use fay\core\Sql;
use fay\models\feed\Meta;
use fay\models\feed\Tag as FeedTag;
use fay\models\feed\File as FeedFile;
use fay\models\tables\Users;

class Feed extends Model{
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
	 * @return Feed
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 判断一个动态ID是否存在（“已删除/未发布/未到定时发布时间”的动态都被视为不存在）
	 * @param int $feed_id
	 * @return bool 若动态已发布且未删除返回true，否则返回false
	 */
	public static function isFeedIdExist($feed_id){
		if($feed_id){
			$feed = Feeds::model()->find($feed_id, 'deleted,publish_time,status');
			if($feed['deleted'] || $feed['publish_time'] > \F::app()->current_time || $feed['status'] != Feeds::STATUS_PUBLISHED){
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
	 *  - user.*系列可指定作者信息，格式参照\fay\models\User::get()
	 * @param bool $only_publish 若为true，则只在已发布的动态里搜索。默认为true
	 */
	public function get($id, $fields = null, $only_published = true){
		$fields || $fields = self::$default_fields;
		//解析$fields
		$fields = FieldHelper::process($fields, 'feed');
		if(empty($fields['feed']) || in_array('*', $fields['feed'])){
			//若未指定返回字段，初始化（默认不返回content，因为列表页基本是不会显示动态详情的）
			$fields['feed'] = Feeds::model()->getFields();
		}
		
		$feed_fields = $fields['feed'];
		if(!empty($fields['user']) && !in_array('user_id', $feed_fields)){
			//如果要获取作者信息，则必须搜出user_id
			$feed_fields[] = 'user_id';
		}
		
		$sql = new Sql();
		$sql->from(array('f'=>Feeds::model()->getTableName()), $feed_fields)
			->where('id = ?', $id);
		
		//仅搜索已发布的动态
		if($only_published){
			$sql->where(array(
				'f.deleted = 0',
				'f.status != '.Feeds::STATUS_DRAFT,
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
			$return['meta'] = Meta::model()->get($id, $fields['meta']);
		}
		
		//作者信息
		if(!empty($fields['user'])){
			$return['user'] = User::model()->get($feed['user_id'], $fields['user']);
		}
		
		//标签
		if(!empty($fields['tags'])){
			$return['tags'] = FeedTag::model()->get($id, $fields['tags']);
		}
		
		//附件
		if(!empty($fields['files'])){
			$return['files'] = FeedFile::model()->get($id, $fields['files']);
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
	 *  - user.*系列可指定作者信息，格式参照\fay\models\User::get()
	 * @param bool $only_publish 若为true，则只在已发布的动态里搜索。默认为true
	 */
	public function mget($feed_ids, $fields, $only_published = true){
		//解析$fields
		$fields = FieldHelper::process($fields, 'feed');
		if(empty($fields['feed']) || in_array('*', $fields['feed'])){
			//若未指定返回字段，初始化（默认不返回content，因为列表页基本是不会显示动态详情的）
			$fields['feed'] = Feeds::model()->getFields();
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
		$sql->from(array('p'=>Feeds::model()->getTableName()), $feed_fields)
			->where('id IN (?)', $feed_ids);
		
		//仅搜索已发布的动态
		if($only_published){
			$sql->where(array(
				'p.deleted = 0',
				'p.status != '.Feeds::STATUS_DRAFT,
				'p.publish_time < '.\F::app()->current_time,
			));
		}
		
		$feeds = $sql->fetchAll();
		
		if(!$feeds){
			return array();
		}
		
		//meta
		if(!empty($fields['meta'])){
			$feed_metas = Meta::model()->mget($feed_ids, $fields['meta']);
		}
		
		//标签
		if(!empty($fields['tags'])){
			$feed_tags = FeedTag::model()->mget($feed_ids, $fields['tags']);
		}
		
		//附件
		if(!empty($fields['files'])){
			$feed_files = FeedFile::model()->mget($feed_ids, $fields['files']);
		}
	}
	
	/**
	 * 判断指定用户是否具备对指定动态的删除权限
	 * @param int|array $feed 动态
	 *  - 若是数组，视为动态表行记录，必须包含user_id字段
	 *  - 若是数字，视为动态ID，会根据ID搜索数据库
	 * @param string $user_id 用户ID，若为空，则默认为当前登录用户
	 * @return bool
	 */
	public function checkDeletePermission($feed, $user_id = null){
		if(!is_array($feed)){
			$feed = Feeds::model()->find($feed, 'user_id');
		}
		$user_id || $user_id = \F::app()->current_user;
		
		if($feed['user_id'] == $user_id){
			//自己的动态总是有权限删除的
			return true;
		}
		
		$user = Users::model()->find($user_id, 'admin');
		if($user['admin']){
			if(User::model()->checkPermission('admin/feed/delete', $user_id)){
				return true;
			}
		}
		
		return false;
	}
}