<?php
namespace guangong\models\tables;

use fay\core\db\Table;

/**
 * Guangong messages table model
 * 
 * @property int $id Id
 * @property int $user_id 用户ID
 * @property string $content 内容
 * @property int $type 类型
 * @property int $create_time 留言时间
 * @property string $reply 管理员回复
 * @property int $reply_time 回复时间
 * @property int $ip_int IP
 * @property int $deleted 删除标记
 */
class GuangongMessagesTable extends Table{
	/**
	 * 类型 - 兵谏
	 */
	const TYPE_BINGJIAN = 1;
	
	/**
	 * 类型 - 公民学者
	 */
	const TYPE_GONGMINXUEZHE = 2;
	
	/**
	 * 类型 - 正义联盟
	 */
	const TYPE_ZHENGYILIANMENG = 3;
	
	protected $_name = 'guangong_messages';
	
	/**
	 * @param string $class_name
	 * @return GuangongMessagesTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('id', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('deleted'), 'range', array('range'=>array(0, 1))),
			array(array('reply_time'), 'datetime'),
			
			array(array('type', 'content'), 'required'),
			array(array('type'), 'range', array('range'=>array(
				GuangongMessagesTable::TYPE_BINGJIAN,
				GuangongMessagesTable::TYPE_GONGMINXUEZHE,
				GuangongMessagesTable::TYPE_ZHENGYILIANMENG,
			))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'user_id'=>'用户ID',
			'content'=>'内容',
			'type'=>'类型',
			'create_time'=>'留言时间',
			'reply'=>'管理员回复',
			'reply_time'=>'回复时间',
			'ip_int'=>'IP',
			'deleted'=>'删除标记',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'user_id'=>'intval',
			'content'=>'',
			'type'=>'intval',
			'reply'=>'',
			'reply_time'=>'trim',
			'deleted'=>'intval',
		);
	}
}