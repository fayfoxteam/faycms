<?php
namespace valentine\models\tables;

use fay\core\db\Table;

/**
 * 用户配对表
 *
 * @property int $id Id
 * @property string $name 组队名称
 * @property int $type 类型
 * @property int $user_id 用户1ID
 * @property int $user_id2 用户2ID
 * @property int $create_time 配对时间
 * @property int $photo 合影图片文件
 * @property string $blessing 对公司的祝福
 * @property int $votes 得票数
 */
class ValentineUserTeamsTable extends Table{
	/**
	 * 类型 - 最具夫妻相
	 */
	const TYPE_COUPLE = 1;
	
	/**
	 * 类型 - 最佳创意奖
	 */
	const TYPE_ORIGINALITY = 1;
	
	/**
	 * 类型 - 最赞祝福语
	 */
	const TYPE_BLESSING = 1;
	
	protected $_name = 'valentine_user_teams';
	
	/**
	 * @param string $class_name
	 * @return ValentineUserTeamsTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('user_id', 'user_id2', 'photo'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('votes'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('type'), 'int', array('min'=>0, 'max'=>255)),
			array(array('name'), 'string', array('max'=>50)),
			array(array('blessing'), 'string', array('max'=>255)),
		);
	}
	
	public function labels(){
		return array(
			'id'=>'Id',
			'name'=>'组队名称',
			'type'=>'类型',
			'user_id'=>'用户1ID',
			'user_id2'=>'用户2ID',
			'create_time'=>'配对时间',
			'photo'=>'合影图片文件',
			'blessing'=>'对公司的祝福',
			'votes'=>'得票数',
		);
	}
	
	public function filters(){
		return array(
			'id'=>'intval',
			'name'=>'trim',
			'type'=>'intval',
			'user_id'=>'intval',
			'user_id2'=>'intval',
			'photo'=>'intval',
			'blessing'=>'trim',
			'votes'=>'intval',
		);
	}
}