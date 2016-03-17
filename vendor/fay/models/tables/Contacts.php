<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Contacts model
 * 
 * @property int $id Id
 * @property string $name 姓名
 * @property string $email Email
 * @property string $phone 电话
 * @property string $title 留言标题
 * @property string $country 国家
 * @property string $content 留言内容
 * @property int $create_time 创建时间
 * @property int $publish_time 发布时间
 * @property int $ip_int IP
 * @property int $show_ip_int 显示给用户看的IP
 * @property int $parent Parent
 * @property int $status Status
 * @property string $reply 回复
 * @property int $is_read 已读标记
 */
class Contacts extends Table{
	protected $_name = 'contacts';
	
	/**
	 * @return Contacts
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('ip_int', 'show_ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('id', 'parent'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('name', 'phone', 'country'), 'string', array('max'=>50)),
			array(array('title'), 'string', array('max'=>255)),
			array(array('is_read'), 'range', array('range'=>array(0, 1))),
			array(array('publish_time'), 'datetime'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'name'=>'真名',
			'email'=>'Email',
			'phone'=>'电话',
			'title'=>'留言标题',
			'country'=>'国家',
			'content'=>'留言内容',
			'create_time'=>'创建时间',
			'publish_time'=>'发布时间',
			'ip_int'=>'IP',
			'show_ip_int'=>'显示给用户看的IP',
			'parent'=>'Parent',
			'status'=>'Status',
			'reply'=>'回复',
			'is_read'=>'已读标记',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'name'=>'trim',
			'email'=>'trim',
			'phone'=>'trim',
			'title'=>'trim',
			'country'=>'trim',
			'content'=>'',
			'create_time'=>'',
			'publish_time'=>'trim',
			'ip_int'=>'intval',
			'show_ip_int'=>'intval',
			'parent'=>'intval',
			'status'=>'intval',
			'reply'=>'',
			'is_read'=>'intval',
		);
	}
}