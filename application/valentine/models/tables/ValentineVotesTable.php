<?php
namespace valentine\models\tables;

use fay\core\db\Table;

/**
 * 投票记录表
 * 
 * @property int $id Id
 * @property int $team_id Team Id
 * @property int $user_id 投票人用户ID
 * @property int $create_time 投票时间
 */
class ValentineVotesTable extends Table{
	protected $_name = 'valentine_votes';
	
	/**
	 * @param string $class_name
	 * @return ValentineVotesTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('team_id'), 'int', array('min'=>-32768, 'max'=>32767)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'team_id'=>'Team Id',
			'user_id'=>'投票人用户ID',
			'create_time'=>'投票时间',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'team_id'=>'intval',
			'user_id'=>'intval',
		);
	}
}