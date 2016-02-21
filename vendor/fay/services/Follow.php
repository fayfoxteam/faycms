<?php
namespace fay\services;

use fay\core\Model;
use fay\core\Hook;
use fay\core\Exception;
use fay\models\tables\Follows;
use fay\helpers\ArrayHelper;
use fay\models\User;
use fay\helpers\Request;
use fay\core\Sql;
use fay\common\ListView;
use fay\helpers\SqlHelper;

class Follow extends Model{
	/**
	 * @return Follow
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 关注一个用户
	 * @param int $user_id 要关注的人
	 * @param null|int $fans_id 粉丝，默认为当前登陆用户
	 * @param int $$sockpuppet 马甲信息
	 */
	public static function follow($user_id, $trackid = '', $fans_id = null, $sockpuppet = 0){
		if($fans_id === null){
			$fans_id = \F::app()->current_user;
		}else if(!User::isUserIdExist($fans_id)){
			throw new Exception('指定粉丝ID不存在', 'the-given-fans-id-is-not-exist');
		}
		
		if($user_id == $fans_id){
			throw new Exception('粉丝和用户ID不能相同', 'can-not-follow-yourself');
		}
		
		if(!User::isUserIdExist($user_id)){
			throw new Exception('关注的用户ID不存在', 'the-given-fans-id-is-not-exist');
		}
		
		if(self::isFollow($user_id, $fans_id)){
			throw new Exception('已关注，不能重复关注', 'already-followed');
		}
		
		$isFollow = self::isFollow($fans_id, $user_id);
		Follows::model()->insert(array(
			'fans_id'=>$fans_id,
			'user_id'=>$user_id,
			'create_time'=>\F::app()->current_time,
			'ip_int'=>Request::ip2int(\F::app()->ip),
			'relation'=>$isFollow ? Follows::RELATION_BOTH : Follows::RELATION_SINGLE,
			'sockpuppet'=>$sockpuppet,
			'trackid'=>$trackid,
		));
		
		if($isFollow){
			//若存在反向关注，则将反向关注记录的relation也置为双向关注
			Follows::model()->update(array(
				'relation'=>Follows::RELATION_BOTH,
			), array(
				'user_id = ?'=>$fans_id,
				'fans_id = ?'=>$user_id,
			));
		}
		
		//执行钩子
		Hook::getInstance()->call('after_follow');
		
		return $isFollow ? Follows::RELATION_BOTH : Follows::RELATION_SINGLE;
	}
	
	/**
	 * 取消关注（若实际在未关注状态，并不抛异常，返回false）
	 * @param int $user_id 取消关注的人
	 * @param null|int $fans_id 粉丝，默认为当前登录用户
	 */
	public static function unfollow($user_id, $fans_id = null){
		$fans_id || $fans_id = \F::app()->current_user;
		if(!$fans_id){
			throw new Exception('未能获取到粉丝ID', 'can-not-find-a-effective-fans-id');
		}
		
		if(self::isFollow($user_id, $fans_id)){//是关注状态，执行取消关注
			//删除关注关系
			Follows::model()->delete(array(
				'fans_id = ?'=>$fans_id,
				'user_id = ?'=>$user_id,
			));
			
			//若互相关注，则更新反向关注的关注关系
			if(self::isFollow($fans_id, $user_id)){
				Follows::model()->update(array(
					'relation'=>Follows::RELATION_SINGLE,
				), array(
					'fans_id = ?'=>$user_id,
					'user_id = ?'=>$fans_id,
				));
			}
			
			//执行钩子
			Hook::getInstance()->call('after_unfollow');
			
			return true;
		}else{//不是关注状态，也不抛异常
			return false;
		}
	}
	
	/**
	 * 判断第二个用户ID是不是关注了第一个用户ID
	 * @param int $user_id 被关注的人ID
	 * @param string $fans_id 粉丝ID，默认为当前登录用户
	 * @return int 0-未关注;1-单向关注;2-双向关注
	 */
	public static function isFollow($user_id, $fans_id = null){
		$fans_id || $fans_id = \F::app()->current_user;
		if(!$fans_id){
			throw new Exception('未能获取到粉丝ID', 'can-not-find-a-effective-fans-id');
		}
		
		$follow = Follows::model()->find(array($fans_id, $user_id), 'relation');
		if($follow){
			return intval($follow['relation']);//为了保证返回字段类型一致，这里intval一下
		}else{
			return 0;
		}
	}
	
	/**
	 * 批量判断是否关注
	 * @param array $user_ids 由用户ID组成的一维数组
	 * @param string $fans_id 粉丝ID，默认为当前登录用户
	 * @return array 根据传入的$user_ids为数组的键，对应值为:0-未关注;1-单向关注;2-双向关注
	 */
	public static function mIsFollow($user_ids, $fans_id = null){
		$fans_id || $fans_id = \F::app()->current_user;
		if(!$fans_id){
			throw new Exception('未能获取到粉丝ID', 'can-not-find-a-effective-fans-id');
		}
		
		$follows = Follows::model()->fetchAll(array(
			'fans_id = ?'=>$fans_id,
			'user_id IN (?)'=>$user_ids,
		), 'user_id,relation');
		
		$follow_map = ArrayHelper::column($follows, 'relation', 'user_id');
		
		$return = array();
		foreach($user_ids as $u){
			$return[$u] = isset($follow_map[$u]) ? intval($follow_map[$u]) : 0;
		}
		return $return;
	}
	
	/**
	 * 关注列表
	 * @param int $user_id 用户ID
	 * @param string $fields 字段
	 * @param int $page 页码
	 * @param int $page_size 分页大小
	 */
	public static function follows($user_id = null, $fields = null, $page = 1, $page_size = 20){
		$user_id || $user_id = \F::app()->current_user;
		$fields || $fields = 'follows.relation,user.id,user.nickname,user.avatar';
		$fields = SqlHelper::processFields($fields, 'follows');
		
		isset($fields['follows']) || $fields['follows'] = array();
		$follows_fields = $fields['follows'];
		if(isset($fields['user']) && !in_array('user_id', $follows_fields)){
			$follows_fields[] = 'user_id';
		}
		
		$sql = new Sql();
		$sql->from('follows', 'f', $follows_fields)
			->where('fans_id = ?', $user_id)
			->order('create_time DESC')
		;
		$listview = new ListView($sql, array(
			'page_size'=>$page_size,
		));
		
		$follows = $listview->getData();
		
		$return = array(
			'follows'=>array(),
			'pager'=>$listview->getPager(),
		);
		
		if($follows && !empty($fields['user'])){
			$users = User::model()->mget(ArrayHelper::column($follows, 'user_id'), implode(',', $fields['user']));
		}
		
		foreach($follows as $f){
			$follow = array();
			foreach($fields['follows'] as $field){
				$follow['follow'][$field] = $f[$field];
			}
			
			if(!empty($fields['user'])){
				$follow['user'] = $users[$f['user_id']];
			}
			
			$return['follows'][] = $follow;
		}
		
		return $return;
	}
	
	/**
	 * 粉丝列表
	 * @param int $user_id 用户ID
	 * @param string $fields 字段
	 * @param int $page 页码
	 * @param int $page_size 分页大小
	 */
	public static function fans($user_id = null, $fields = null, $page = 1, $page_size = 20){
		$user_id || $user_id = \F::app()->current_user;
		$fields || $fields = 'follows.relation,user.id,user.nickname,user.avatar';
		$fields = SqlHelper::processFields($fields, 'follows');
		
		isset($fields['follows']) || $fields['follows'] = array();
		$follows_fields = $fields['follows'];
		if(isset($fields['user']) && !in_array('fans_id', $follows_fields)){
			$follows_fields[] = 'fans_id';
		}
		
		$sql = new Sql();
		$sql->from('follows', 'f', $follows_fields)
			->where('user_id = ?', $user_id)
			->order('create_time DESC')
		;
		$listview = new ListView($sql, array(
			'page_size'=>$page_size,
		));
		
		$fans = $listview->getData();
		
		$return = array(
			'fans'=>array(),
			'pager'=>$listview->getPager(),
		);
		
		if($fans && !empty($fields['user'])){
			$users = User::model()->mget(ArrayHelper::column($fans, 'fans_id'), implode(',', $fields['user']));
		}
		
		foreach($fans as $f){
			$follow = array();
			foreach($fields['follows'] as $field){
				$follow['follow'][$field] = $f[$field];
			}
			
			if(!empty($fields['user'])){
				$follow['user'] = $users[$f['fans_id']];
			}
			
			$return['fans'][] = $follow;
		}
		
		return $return;
	}
}