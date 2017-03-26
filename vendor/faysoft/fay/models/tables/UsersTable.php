<?php
namespace fay\models\tables;

use fay\core\db\Table;
use fay\services\OptionService;

/**
 * Users table model
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
 * @property int $delete_time 删除时间
 * @property int $admin
 */
class UsersTable extends Table{
	/**
	 * 状态-用户信息不完整
	 */
	const STATUS_UNCOMPLETED = 0;

	/**
	 * 状态-等待人工审核
	 */
	const STATUS_PENDING = 1;

	/**
	 * 状态-未通过人工审核
	 */
	const STATUS_VERIFY_FAILED = 2;

	/**
	 * 状态-通过审核
	 */
	const STATUS_VERIFIED = 3;

	/**
	 * 状态-未验证邮箱
	 */
	const STATUS_NOT_VERIFIED = 4;

	/**
	 * 特殊记录-系统用户
	 */
	const ITEM_SYSTEM = 1;
	
	/**
	 * 特殊记录-用户留言收件人
	 */
	const ITEM_USER_MESSAGE_RECEIVER = 2;
	
	/**
	 * 特殊记录-系统消息用户
	 */
	const ITEM_SYSTEM_NOTIFICATION = 3;

	protected $_name = 'users';

	/**
	 * @param string $class_name
	 * @return UsersTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		$rules = array(
			array(array('id', 'avatar'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('parent'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('username', 'nickname', 'realname'), 'string', array('max'=>50)),
			array(array('password'), 'string', array('max'=>32)),
			array(array('salt'), 'string', array('max'=>5)),
			array(array('delete_time'), 'range', array('range'=>array(0, 1))),
			array(array('mobile'), 'mobile'),

			array('username', 'unique', array('on'=>'create', 'table'=>'users', 'field'=>'username', 'ajax'=>array('api/user/is-username-not-exist'))),
			array('username', 'required', array('on'=>'create')),
			array('username', 'unique', array('on'=>'edit', 'table'=>'users', 'field'=>'username', 'except'=>'id', 'ajax'=>array('api/user/is-username-not-exist'))),
			array(array('email'), 'email'),
			array(array('block', 'admin'), 'range', array('range'=>array(0, 1))),
		);
		
		if(OptionService::get('system:user_nickname_required')){
			$rules[] = array('nickname', 'required', array('on'=>'create'));
		}
		
		if(OptionService::get('system:user_nickname_unique')){
			$rules[] = array('nickname', 'unique', array('on'=>'create', 'table'=>'users', 'field'=>'nickname', 'ajax'=>array('api/user/is-nickname-not-exist')));
			$rules[] = array('nickname', 'unique', array('on'=>'edit', 'table'=>'users', 'field'=>'username', 'except'=>'id', 'ajax'=>array('api/user/is-nickname-not-exist')));
		}
		
		return $rules;
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
			'realname'=>'姓名',
			'avatar'=>'头像',
			'status'=>'用户审核状态',
			'block'=>'屏蔽用户',
			'parent'=>'父节点',
			'delete_time'=>'删除时间',
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
			'realname'=>'trim',
			'avatar'=>'intval',
			'status'=>'intval',
			'block'=>'intval',
			'parent'=>'intval',
			'delete_time'=>'intval',
			'admin'=>'intval',
		);
	}
	
	public function getNotWritableFields($scene){
		switch($scene){
			case 'insert':
				return array(
					'id',
				);
			case 'update':
			default:
				return array(
					'id', 'username', 'admin'
				);
		}
	}
}