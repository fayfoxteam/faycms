<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Contacts model
 * 
 * @property int $id Id
 * @property string $name 姓名
 * @property string $email Email
 * @property string $mobile 电话
 * @property string $title 留言标题
 * @property string $country 国家
 * @property string $content 留言内容
 * @property int $create_time 创建时间
 * @property int $publish_time 发布时间
 * @property int $ip_int 真实IP
 * @property int $show_ip_int 显示IP
 * @property int $parent Parent
 * @property string $reply 回复
 * @property int $is_show 前台显示
 * @property int $is_read 已读标记
 */
class Contacts extends Table{
	protected $_name = 'contacts';
	
	/**
	 * @param string $class_name
	 * @return Contacts
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id', 'parent'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('name', 'country'), 'string', array('max'=>50)),
			array(array('title'), 'string', array('max'=>255)),
			array(array('is_show', 'is_read'), 'range', array('range'=>array(0, 1))),
			array(array('publish_time'), 'datetime'),
			array(array('mobile'), 'mobile'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'name'=>'真名',
			'email'=>'Email',
			'mobile'=>'电话',
			'title'=>'留言标题',
			'country'=>'国家',
			'content'=>'留言内容',
			'create_time'=>'创建时间',
			'publish_time'=>'发布时间',
			'ip_int'=>'真实IP',
			'show_ip_int'=>'显示IP',
			'parent'=>'Parent',
			'reply'=>'回复',
			'is_show'=>'前台显示',
			'is_read'=>'已读标记',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'name'=>'trim',
			'email'=>'trim',
			'mobile'=>'trim',
			'title'=>'trim',
			'country'=>'trim',
			'content'=>'',
			'publish_time'=>'trim',
			'show_ip_int'=>'trim|\fay\helpers\Request::ip2int',
			'parent'=>'intval',
			'reply'=>'',
			'is_show'=>'intval',
			'is_read'=>'intval',
		);
	}
}