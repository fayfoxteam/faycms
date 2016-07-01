<?php
namespace fay\services\user;

use fay\core\Service;
use fay\models\tables\UserProfile;

class Profile extends Service{
	/**
	 * 默认返回字段
	 */
	private $default_fields = array('reg_time', 'last_login_time', 'last_login_ip', 'last_time_online');
	
	/**
	 * @param string $class_name
	 * @return Profile
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 获取用户信息
	 * @param int $user_id 用户ID
	 * @param string $fields 附件字段（user_profile表字段）
	 * @return array 返回包含用户profile信息的二维数组
	 */
	public function get($user_id, $fields = null){
		if(empty($fields) || empty($fields[0])){
			//若传入$fields为空，则返回默认字段
			$fields = $this->default_fields;
		}
		return UserProfile::model()->fetchRow(array(
			'user_id = ?'=>$user_id,
		), $fields);
	}
	
	/**
	 * 批量获取用户信息
	 * @param array $user_ids 用户ID一维数组
	 * @param string $fields 附件字段（user_profile表字段）
	 * @return array 返回以用户ID为key的三维数组
	 */
	public function mget($user_ids, $fields = null){
		if(empty($fields) || empty($fields[0])){
			//若传入$fields为空，则返回默认字段
			$fields = $this->default_fields;
		}
		//批量搜索，必须先得到user_id
		if(!is_array($fields)){
			$fields = explode(',', $fields);
		}
		if(!in_array('user_id', $fields)){
			$fields[] = 'user_id';
			$remove_user_id = true;
		}else{
			$remove_user_id = false;
		}
		$profiles = UserProfile::model()->fetchAll(array(
			'user_id IN (?)'=>$user_ids,
		), $fields, 'user_id');
		$return = array_fill_keys($user_ids, array());
		foreach($profiles as $p){
			$u = $p['user_id'];
			if($remove_user_id){
				unset($p['user_id']);
			}
			$return[$u] = $p;
		}
		return $return;
	}
}