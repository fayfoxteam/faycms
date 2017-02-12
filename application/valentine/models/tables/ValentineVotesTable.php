<?php
namespace valentine\models\tables;

use fay\core\db\Table;

/**
 * 投票记录表
 *
 * @property int $id Id
 * @property int $team_id 组合ID
 * @property string $open_id 投票人OpenID
 * @property int $type 类型
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
			array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('team_id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('type'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('open_id'), 'string', array('max'=>50)),
		);
	}
	
	public function labels(){
		return array(
			'id'=>'Id',
			'team_id'=>'组合ID',
			'open_id'=>'投票人OpenID',
			'type'=>'类型',
			'create_time'=>'投票时间',
		);
	}
	
	public function filters(){
		return array(
			'id'=>'intval',
			'team_id'=>'intval',
			'open_id'=>'trim',
			'type'=>'intval',
		);
	}
}