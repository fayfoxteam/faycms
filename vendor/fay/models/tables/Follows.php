<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Follows model
 * 
 * @property int $fans_id
 * @property int $user_id
 * @property int $create_time
 * @property int $relation
 * @property int $is_real
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
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('fans_id', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('is_real'), 'range', array('range'=>array(0, 1))),

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
		);
	}

	public function filters(){
		return array(
			'fans_id'=>'intval',
			'user_id'=>'intval',
			'create_time'=>'',
			'relation'=>'intval',
			'is_real'=>'intval',
		);
	}
}