<?php
namespace fay\services\user;

use fay\core\ErrorException;
use fay\core\HttpException;
use fay\core\Service;
use fay\models\tables\UserConnectsTable;
use fay\models\tables\UsersTable;
use fay\services\FileService;
use fay\services\OauthAppService;
use fay\services\oauth\OAuthException;
use fay\services\oauth\UserAbstract;

class UserOauthService extends Service{
	/**
	 * @param string $class_name
	 * @return UserOauthService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 通过App Id和Open Id判断此用户是否在本地注册过
	 *  - 若注册过，返回用户ID
	 *  - 若没注册过，返回字符串0
	 * @param string $app_id
	 * @param string $open_id
	 * @return int
	 */
	public function isLocalUser($app_id, $open_id){
		$user = UserConnectsTable::model()->fetchRow(array(
			'open_id = ?'=>$open_id,
			'oauth_app_id = ?'=>OauthAppService::service()->getIdByAppId($app_id),
		), 'user_id');
		
		if($user){
			return $user['user_id'];
		}else{
			return '0';
		}
	}
	
	/**
	 * 用第三方用户信息，创建本地用户。返回用户ID。
	 * @param UserAbstract $user 第三方用户信息
	 * @param int $status 用户状态
	 * @return int
	 * @throws OAuthException
	 * @throws UserException
	 */
	public function createUser(UserAbstract $user, $status = UsersTable::STATUS_VERIFIED){
		if($this->isLocalUser($user->getAccessToken()->getAppId(), $user->getOpenId())){
			throw new OAuthException('用户已存在，不能重复创建');
		}
		
		if($user->getUnionId()){
			/**
			 * 若存在Union Id，判断Union Id是否存在
			 *  - 若存在，不创建新用户
			 */
			$user_connect = UserConnectsTable::model()->fetchRow(array(
				'unionid = ?'=>$user->getUnionId(),
			));
			if($user_connect){
				$user_id = $user_connect['user_id'];
			}
		}
		
		if(empty($user_id)){
			//若是新用户，插用户表
			$user_id = UserService::service()->create(array(
				'status'=>$status,
				'avatar'=>$this->getLocalAvatar($user->getAvatar()),
				'nickname'=>$user->getNickName(),
			));
		}
		
		UserConnectsTable::model()->insert(array(
			'user_id'=>$user_id,
			'open_id'=>$user->getOpenId(),
			'unionid'=>$user->getUnionId(),
			'oauth_app_id'=>OauthAppService::service()->getIdByAppId($user->getAccessToken()->getAppId()),
			'access_token'=>$user->getAccessToken()->getAccessToken(),
			'expires_in'=>$user->getAccessToken()->getExpires(),
			'refresh_token'=>$user->getAccessToken()->getRefreshToken(),
			'create_time'=>\F::app()->current_time,
		));
		
		return $user_id;
	}
	
	/**
	 * 从远程将头像下载到本地后，返回本地文件ID
	 * @param string $avatar_url 远程头像url
	 * @return int
	 * @throws ErrorException
	 * @throws HttpException
	 */
	public function getLocalAvatar($avatar_url){
		if($avatar_url){
			$avatar_file = FileService::service()->uploadFromUrl($avatar_url);
			if($avatar_file['status']){
				return $avatar_file['data']['id'];
			}else{
				return '0';
			}
		}else{
			return '0';
		}
	}
	
	/**
	 * 根据App Id获取用户的Open Id
	 * @param string $app_id 第三方App Id
	 * @param null|int $user_id 用户ID（默认为当前登录用户ID）
	 * @return string
	 * @throws OAuthException
	 */
	public function getOpenId($app_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new OAuthException('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		$oauth_app_id = OauthAppService::service()->getIdByAppId($app_id);
		if(!$oauth_app_id){
			return '';
		}
		
		$connect = UserConnectsTable::model()->fetchRow(array(
			'user_id = ?'=>$user_id,
			'oauth_app_id = ?'=>$oauth_app_id,
		), 'open_id');
		
		return $connect ? $connect['open_id'] : '';
	}
}