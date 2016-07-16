<?php
namespace fay\services\post;

use fay\core\Service;
use fay\core\Sql;
use fay\models\tables\Posts;
use fay\services\user\Counter;
use fay\models\tables\UserCounter as UserCounterModel;

class UserCounter extends Service{
	/**
	 * @param string $class_name
	 * @return UserCounter
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 递增一个或多个指定用户的计数
	 * @param array|int $user_ids
	 * @param int $value 增量，默认为1，正数表示递减
	 * @return int
	 */
	public function incr($user_ids, $value = 1){
		Counter::service()->incr($user_ids, 'posts', $value);
	}
	
	/**
	 * 递减一个或多个指定用户的计数
	 * @param array|int $user_ids
	 * @param int $value 增量，默认为1，正数表示递减
	 * @return int
	 */
	public function decr($user_ids, $value = 1){
		return $this->incr($user_ids, -$value);
	}
	
	/**
	 * 通过计算获取指定用户的文章数
	 * @param int $user_id 用户ID
	 * @return int
	 */
	public function getPostCount($user_id){
		$sql = new Sql();
		$result = $sql->from(array('p'=>'posts'), 'COUNT(*)')
			->where('p.user_id = ?', $user_id)
			->where(array(
				'p.deleted = 0',
				'p.status = '.Posts::STATUS_PUBLISHED,
				'p.publish_time < '.\F::app()->current_time,
			))
			->fetchRow();
		return $result['COUNT(*)'];
	}
	
	/**
	 * 重置用户文章数
	 * （目前都是小网站，且只有出错的时候才需要回复，所以不做分批处理）
	 */
	public function resetPostCount(){
		$sql = new Sql();
		$results = $result = $sql->from(array('p'=>'posts'), array('user_id', 'COUNT(*) AS count'))
			->where(array(
				'p.deleted = 0',
				'p.status = '.Posts::STATUS_PUBLISHED,
				'p.publish_time < '.\F::app()->current_time,
			))
			->group('p.user_id')
			->fetchAll();
		
		//先清零
		UserCounterModel::model()->update(array(
			'posts'=>0
		), false);
		
		foreach($results as $r){
			UserCounterModel::model()->update(array(
				'posts'=>$r['count']
			), $r['user_id']);
		}
	}
}