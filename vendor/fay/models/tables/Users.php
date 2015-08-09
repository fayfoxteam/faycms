<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Users model
 * 
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $mobile
 * @property string $password
 * @property string $salt
 * @property string $nickname
 * @property int $avatar
 * @property int $status
 * @property int $block
 * @property int $parent
 * @property int $deleted
 * @property int $admin
 */
class Users extends Table{
	/**
	 * 用户信息不完整
	 * @var int
	 */
	const STATUS_UNCOMPLETED = 0;

	/**
	 * 等待人工审核
	 * @var int
	 */
	const STATUS_PENDING = 1;

	/**
	 * 未通过人工审核
	 * @var int
	 */
	const STATUS_VERIFY_FAILED = 2;

	/**
	 * 通过审核
	 * @var int
	 */
	const STATUS_VERIFIED = 3;

	/**
	 * 未验证邮箱
	 * @var int
	 */
	const STATUS_NOT_VERIFIED = 4;

	/**
	 * 系统用户
	 */
	const ITEM_SYSTEM = 1;
	
	/**
	 * 用户留言收件人
	 */
	const ITEM_USER_MESSAGE_RECEIVER = 2;
	
	/**
	 * 系统消息用户
	 */
	const ITEM_SYSTEM_NOTIFICATION = 3;

	protected $_name = 'users';

	/**
	 * @return Users
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id', 'avatar'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('parent'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('username', 'nickname'), 'string', array('max'=>50)),
			array(array('password'), 'string', array('max'=>32)),
			array(array('salt'), 'string', array('max'=>5)),
			array(array('deleted'), 'range', array('range'=>array('0', '1'))),
			array(array('mobile'), 'mobile'),

			array(array('username'), 'unique', array('on'=>'create', 'table'=>'users', 'field'=>'username', 'ajax'=>array('tools/user/is-username-not-exist'))),
			array(array('username'), 'unique', array('on'=>'edit', 'table'=>'users', 'field'=>'username', 'except'=>'id', 'ajax'=>array('tools/user/is-username-not-exist'))),
			array(array('email'), 'email'),
			array(array('block', 'admin'), 'range', array('range'=>array('0', '1'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'username'=>'登录名',
			'email'=>'邮箱',
			'mobile'=>'手机号码',
			'password'=>'密码',
			'salt'=>'五位随机数',
			'nickname'=>'昵称',
			'avatar'=>'头像',
			'status'=>'用户审核状态',
			'block'=>'屏蔽用户',
			'parent'=>'父节点',
			'deleted'=>'Deleted',
			'admin'=>'是否为管理员',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'username'=>'trim',
			'email'=>'trim',
			'mobile'=>'trim',
			'password'=>'trim',
			'salt'=>'trim',
			'nickname'=>'trim',
			'avatar'=>'intval',
			'status'=>'intval',
			'block'=>'intval',
			'parent'=>'intval',
			'deleted'=>'intval',
			'admin'=>'intval',
		);
	}
}