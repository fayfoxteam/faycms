<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Follows model
 * 
 * @property int $fans_id 粉丝ID
 * @property int $user_id 用户ID
 * @property int $create_time 关注时间
 * @property int $relation 单向/双向关注
 * @property int $is_real 是否真实用户
 * @property string $follow_from 关注来源
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
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('fans_id', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('is_real'), 'range', array('range'=>array(0, 1))),
			array(array('follow_from'), 'string', array('max'=>30)),

			array(array('relation'), 'range', array('range'=>array(1, 2))),
		);
	}

	public function labels(){
		return array(
			'fans_id'=>'粉丝ID',
			'user_id'=>'用户ID',
			'create_time'=>'关注时间',
			'relation'=>'单向/双向关注',
			'is_real'=>'是否真实用户',
			'follow_from'=>'关注来源',
		);
	}

	public function filters(){
		return array(
			'fans_id'=>'intval',
			'user_id'=>'intval',
			'create_time'=>'',
			'relation'=>'intval',
			'is_real'=>'intval',
			'follow_from'=>'trim',
		);
	}
}