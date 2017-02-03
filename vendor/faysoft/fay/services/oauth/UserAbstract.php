<?php
namespace fay\services\oauth;

/**
 * 用于统一第三方登陆获取用户信息方式
 */
abstract class UserAbstract implements \ArrayAccess{
	/**
	 * @var array
	 */
	private $params = array();
	
	/**
	 * @var AccessTokenAbstract
	 */
	private $access_token;
	
	public function __construct(array $params, AccessTokenAbstract $access_token){
		$this->params = $params;
		$this->access_token = $access_token;
	}
	
	/**
	 * 第三方类型。对应user_connects表的type字段
	 * @return int
	 */
	abstract public function getType();
	
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