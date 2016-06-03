<?php
namespace fay\models\user;

use fay\models\tables\Props;

class Prop extends \fay\models\Prop{
	/**
	 * @param string $class_name
	 * @return Prop
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * @see Prop::$models
	 * @var array
	 */
	protected $models = array(
		'varchar'=>'fay\models\tables\UserPropVarchar',
		'int'=>'fay\models\tables\UserPropInt',
		'text'=>'fay\models\tables\UserPropText',
	);
	
	/**
	 * @see Prop::$foreign_key
	 * @var string
	 */
	protected $foreign_key = 'user_id';
	
	/**
	 * @see Prop::$type
	 * @var string
	 */
	protected $type = Props::TYPE_ROLE;
	
	/**
	 * @see \fay\models\Prop::setValue()
	 * @param string $alias
	 * @param mixed $value
	 * @param null|int $user_id 若为null，则默认为当前登录用户
	 * @return bool
	 */
	public function setValue($alias, $value, $user_id = null)
	{
		$user_id || $user_id = \F::app()->current_user;
		return parent::setValue($alias, $value, $user_id);
	}
	
	/**
	 * @see \fay\models\Prop::getValue()
	 * @param string $alias
	 * @param null|int $user_id 若为null，则默认为当前登录用户
	 * @return mixed
	 */
	public function getValue($alias, $user_id = null)
	{
		$user_id || $user_id = \F::app()->current_user;
		return parent::getValue($alias, $user_id);
	}
	
	/**
	 * 新增一个用户属性集
	 * @param int $user_id 用户ID
	 * @param array $data 以属性ID为键的属性键值数组
	 * @param null|array $props 属性。若为null，则根据用户ID获取属性
	 */
	public function createPropertySet($user_id, $data, $props = null){
		if($props === null){
			$props = UserModel::model()->getProps($user_id);
		}
		Prop::model()->createPropertySet($user_id, $props, $data);
	}
	
	/**
	 * 更新一个用户属性集
	 * @param int $user_id 用户ID
	 * @param array $data 以属性ID为键的属性键值数组
	 * @param null|array $props 属性。若为null，则根据用户ID获取属性
	 */
	public function updatePropertySet($user_id, $data, $props = null){
		if($props === null){
			$props = UserModel::model()->getProps($user_id);
		}
		Prop::model()->updatePropertySet($user_id, $props, $data);
	}
}