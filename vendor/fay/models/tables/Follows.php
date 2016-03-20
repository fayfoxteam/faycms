<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Follows model
 * 
 * @property int $fans_id 粉丝ID
 * @property int $user_id 用户ID
 * @property int $create_time 关注时间
 * @property int $ip_int IP
 * @property int $relation 单向/双向关注
 * @property int $sockpuppet 马甲信息
 * @property string $trackid 追踪ID
 */
class Follows extends Table{
	/**
	 * 单向关注
	 */
	const RELATION_SINGLE = 1;
	
	/**
	 * 双向关注
	 */
	const RELATION_BOTH = 2;
	
	protected $_name = 'follows';
	protected $_primary = array('fans_id', 'user_id');
	
	/**
	 * @return Follows
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('fans_id', 'user_id', 'sockpuppet'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('trackid'), 'string', array('max'=>50)),

			array(array('relation'), 'range', array('range'=>array(1, 2))),
		);
	}

	public function labels(){
		return array(
			'fans_id'=>'粉丝ID',
			'user_id'=>'用户ID',
			'create_time'=>'关注时间',
			'ip_int'=>'IP',
			'relation'=>'单向/双向关注',
			'sockpuppet'=>'马甲信息',
			'trackid'=>'追踪ID',
		);
	}

	public function filters(){
		return array(
			'fans_id'=>'intval',
			'user_id'=>'intval',
			'relation'=>'intval',
			'sockpuppet'=>'intval',
			'trackid'=>'trim',
		);
	}
}