<?php
namespace fay\models\user;

use fay\models\tables\Props;

class Prop extends \fay\models\Prop{
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
		return parent::getValue($alias, $user_id);
	}
}