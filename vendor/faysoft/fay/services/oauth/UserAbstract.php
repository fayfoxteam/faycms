<?php
namespace fay\services\oauth;
use fay\models\tables\UserConnectsTable;
use fay\models\tables\UsersTable;
use fay\services\FileService;
use fay\services\user\UserService;

/**
 * 用于统一第三方登陆获取用户信息方式
 */
class UserAbstract implements \ArrayAccess{
	/**
	 * @var array
	 */
	private $params = array();
	
	/**
	 * @var AccessTokenAbstract
	 */
	private $access_token;
	
	public function __construct($params, $access_token){
		$this->params = $params;
		$this->access_token = $access_token;
	}
	
	/**
	 * 创建本地用户，返回用户ID
	 * @param int $status 默认用户状态
	 * @return int
	 * @throws \fay\services\user\UserException
	 */
	public function createLocalUser($status = UsersTable::STATUS_VERIFIED){
		$user_id = UserService::service()->create(array(
			'status'=>$status,
			'avatar'=>$this->getLocalAvatar(),
			'nickname'=>$this->getNickName(),
		));
		UserConnectsTable::model()->insert(array(
			'user_id'=>$user_id,
			'openid'=>$this->getOpenId(),
			'unionid'=>$this->getUnionId(),
			'app_id'=>$this->getAccessToken()->getAppId(),
			'access_token'=>$this->getAccessToken()->getAccessToken(),
			'expires_in'=>$this->getAccessToken()->getExpires(),
			'refresh_token'=>$this->getAccessToken()->getRefreshToken(),
			'create_time'=>\F::app()->current_time,
		));
		
		return $user_id;
	}
	
	/**
	 * 获取Access Token
	 * @return AccessTokenAbstract
	 */
	public function getAccessToken(){
		return $this->access_token;
	}
	
	/**
	 * 获取用户昵称。
	 * 若第三方字段名特殊，可在子类中重写此方法。
	 * @return string
	 */
	public function getNickName(){
		return $this->getParam('nickname');
	}
	
	/**
	 * 获取openId。
	 * 若第三方字段名特殊，可在子类中重写此方法。
	 * @return string
	 */
	public function getOpenId(){
		return $this->getParam('openid');
	}
	
	/**
	 * 微信有这个值
	 * @return string
	 */
	public function getUnionId(){
		return $this->getParam('unionid');
	}
	
	/**
	 * 获取用户头像。
	 * 若第三方字段名特殊，可在子类中重写此方法。
	 * @return string
	 */
	public function getAvatar(){
		return $this->getParam('headimgurl');
	}
	
	/**
	 * 从远程将头像下载到本地后，返回本地文件ID
	 * @return int
	 */
	public function getLocalAvatar(){
		$avatar_url = $this->getAvatar();
		if($avatar_url){
			$avatar_file = FileService::service()->uploadFromUrl($avatar_url);
			return $avatar_file['id'];
		}else{
			return '0';
		}
	}
	
	/**
	 * 获取$this->params参数
	 * @param string $key
	 * @return string
	 */
	public function getParam($key){
		return isset($this->params[$key]) ? $this->params[$key] : '';
	}
	
	/**
	 * 获取所有字段
	 * @return array
	 */
	public function getParams(){
		return $this->params;
	}
	
	/**
	 * Whether a offset exists
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 * An offset to check for.
	 * </p>
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 * @since 5.0.0
	 */
	public function offsetExists($offset)
	{
		return isset($this->params[$offset]);
	}
	
	/**
	 * Offset to retrieve
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset <p>
	 * The offset to retrieve.
	 * </p>
	 * @return mixed Can return all value types.
	 * @since 5.0.0
	 */
	public function offsetGet($offset)
	{
		$this->getParam($offset);
	}
	
	/**
	 * Offset to set
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset <p>
	 * The offset to assign the value to.
	 * </p>
	 * @param mixed $value <p>
	 * The value to set.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetSet($offset, $value)
	{
		$this->params[$offset] = $value;
	}
	
	/**
	 * Offset to unset
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 * The offset to unset.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetUnset($offset)
	{
		unset($this->params[$offset]);
	}
}