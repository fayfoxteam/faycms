<?php
namespace fay\models\tables;

use fay\core\db\Table;

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
	 * 普通用户
	 * @var int
	 */
	const ROLE_USER = 1;

	/**
	 * 账单用户（定制化需求）
	 * @var int
	 */
	const ROLE_BILL = 2;

	/**
	 * 系统
	 * @var int
	 */
	const ROLE_SYSTEM = 100;
	
	/**
	 * 超级管理员<br>
	 * 100以上的角色为管理员角色
	 * @var int
	 */
	const ROLE_SUPERADMIN = 101;
	
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
			array(array('reg_ip', 'last_login_ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('id', 'avatar', 'last_login_time', 'last_time_online'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('parent'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('login_times'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('status', 'block'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('role'), 'int', array('min'=>0, 'max'=>255)),
			array(array('username', 'realname', 'nickname', 'trackid'), 'string', array('max'=>50)),
			array(array('password'), 'string', array('max'=>32)),
			array(array('salt'), 'string', array('max'=>5)),
			array(array('refer', 'keywords'), 'string', array('max'=>255)),
			array(array('se'), 'string', array('max'=>30)),
			array(array('deleted'), 'range', array('range'=>array('0', '1'))),
			array(array('cellphone'), 'mobile'),

			array(array('username'), 'unique', array('on'=>'create', 'table'=>'users', 'field'=>'username', 'ajax'=>array('tools/user/is-username-not-exist'))),
			array(array('username'), 'unique', array('on'=>'edit', 'table'=>'users', 'field'=>'username', 'except'=>'id', 'ajax'=>array('tools/user/is-username-not-exist'))),
			array(array('refer'), 'url'),
			array(array('email'), 'email'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'username'=>'登录名',
			'email'=>'邮箱',
			'cellphone'=>'手机号码',
			'password'=>'密码',
			'salt'=>'五位随机数',
			'realname'=>'用户名',
			'nickname'=>'昵称',
			'professional'=>'专业',
			'sex'=>'性别',
			'avatar'=>'头像',
			'reg_time'=>'注册时间',
			'reg_ip'=>'注册ip',
			'login_times'=>'登陆次数',
			'last_login_time'=>'最后登陆时间',
			'last_login_ip'=>'最后登陆者ip',
			'last_time_online'=>'最后在线时间',
			'status'=>'用户审核状态',
			'block'=>'屏蔽用户',
			'role'=>'角色',
			'parent'=>'父节点',
			'deleted'=>'Deleted',
			'trackid'=>'Trackid',
			'refer'=>'来源URL',
			'se'=>'搜索引擎',
			'keywords'=>'搜索关键词',
		);
	}

	public function filters(){
		return array(
			'username'=>'trim',
			'email'=>'trim',
			'cellphone'=>'trim',
			'password'=>'trim',
			'salt'=>'trim',
			'realname'=>'trim',
			'nickname'=>'trim',
			'professional'=>'trim',
			'sex'=>'trim',
			'avatar'=>'intval',
			'reg_time'=>'trim',
			'reg_ip'=>'intval',
			'login_times'=>'trim',
			'last_login_time'=>'trim',
			'last_login_ip'=>'intval',
			'last_time_online'=>'trim',
			'status'=>'intval',
			'block'=>'intval',
			'role'=>'intval',
			'parent'=>'intval',
			'deleted'=>'intval',
			'trackid'=>'trim',
			'refer'=>'trim',
			'se'=>'trim',
			'keywords'=>'trim',
		);
	}
}