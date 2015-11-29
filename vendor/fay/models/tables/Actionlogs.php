<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Actionlogs extends Table{
	/**
	 * 模版
	 */
	const TYPE_TEMPLATE = 1;

	/**
	 * 个人信息
	 */
	const TYPE_PROFILE = 4;

	/**
	 * 管理员信息
	 */
	const TYPE_ACTION = 5;

	/**
	 * 分类信息
	 */
	const TYPE_CATEGORY = 6;

	/**
	 * 文件操作
	 */
	const TYPE_FILE = 9;

	/**
	 * 友情链接
	 */
	const TYPE_LINK = 10;

	/**
	 * 后台登陆
	 */
	const TYPE_LOGIN = 11;

	/**
	 * 留言
	 */
	const TYPE_MESSAGE = 12;

	/**
	 * 系统信息
	 */
	const TYPE_NOTIFICATION = 13;

	/**
	 * 用户信息
	 */
	const TYPE_USERS = 14;

	/**
	 * 文章管理
	 */
	const TYPE_POST = 16;

	/**
	 * 页面管理
	 */
	const TYPE_PAGE = 17;

	/**
	 * 订单管理
	 */
	const TYPE_ORDER = 18;

	/**
	 * 商品管理
	 */
	const TYPE_GOODS = 19;

	/**
	 * 商品属性管理
	 */
	const TYPE_GOODS_PROP = 20;

	/**
	 * 角色管理
	 */
	const TYPE_ROLE = 21;

	/**
	 * 标签管理
	 */
	const TYPE_TAG = 22;

	/**
	 * 文章分类属性
	 */
	const TYPE_POST_CAT = 27;

	/**
	 * 小工具
	 */
	const TYPE_WIDGET = 28;

	/**
	 * 角色属性
	 */
	const TYPE_ROLE_PROP = 29;

	/**
	 * 用户留言
	 */
	const TYPE_CONTACT = 30;

	/**
	 * 菜单管理
	 */
	const TYPE_MENU = 31;

	/**
	 * 试题管理
	 */
	const TYPE_EXAM = 32;

	protected $_name = 'actionlogs';
	
	/**
	 * @return Actionlogs
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('id', 'create_time', 'refer'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('user_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('type'), 'int', array('min'=>0, 'max'=>255)),
			array(array('note'), 'string', array('max'=>255)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'user_id'=>'用户ID',
			'type'=>'日志类型',
			'note'=>'日志内容',
			'create_time'=>'创建时间',
			'refer'=>'关联ID',
			'ip_int'=>'IP',
		);
	}

	public function filters(){
		return array(
			'user_id'=>'intval',
			'type'=>'intval',
			'note'=>'trim',
			'create_time'=>'',
			'refer'=>'intval',
			'ip_int'=>'intval',
		);
	}
}