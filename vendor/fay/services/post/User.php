<?php
namespace fay\services\post;

use fay\core\Service;

class User extends Service{
	/**
	 * 默认返回用户字段
	 */
	public static $default_fields = array(
		'user'=>array(
			'fields'=>array(
				'id', 'nickname', 'avatar',
			)
		)
	);
	
	/**
	 * @param string $class_name
	 * @return User
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 将user信息装配到$posts中
	 * @param array $posts 包含文章信息的三维数组
	 *   若包含$posts.post.id字段，则以此字段作为文章ID
	 *   若不包含$posts.post.id，则以$posts的键作为文章ID
	 * @param null|string $fields
	 * @throws Exception
	 */
	public function assemble(&$posts, $fields = null){
		if(empty($fields)){
			//若传入$fields为空，则返回默认字段
			$fields = self::$default_fields;
		}
		
		//获取所有用户ID
		$user_ids = array();
		foreach($posts as $k => $p){
			if(isset($p['post']['user_id'])){
				$user_ids[] = $p['post']['user_id'];
			}else{
				throw new Exception(__CLASS__.'::'.__FUNCTION__.'()方法$posts参数中，必须包含user_id项');
			}
		}
		
		$user_map = \fay\services\User::service()->mget($user_ids, $fields);
		
		foreach($posts as $k => $p){
			$p['user'] = $user_map[$p['post']['user_id']];
			
			$posts[$k] = $p;
		}
	}
}