<?php
namespace guangong\models\tables;

use fay\core\db\Table;

/**
 * 军衔表
 *
 * @property int $id Id
 * @property string $name 兵制
 * @property string $captain 统兵官
 * @property int $soldiers 统领士兵数
 * @property int $months 获得军衔规则：月
 * @property int $times 获得军衔规则：累计次数
 * @property int $continuous 获得军衔规则：连续签到天数
 * @property int $sort 军衔高低（值越高表示军衔越高）
 */
class GuangongRanksTable extends Table{
	protected $_name = 'guangong_ranks';
	
	/**
	 * @param string $class_name
	 * @return GuangongRanksTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('soldiers'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('id', 'times', 'continuous', 'sort'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('months'), 'int', array('min'=>0, 'max'=>255)),
			array(array('name'), 'string', array('max'=>10)),
			array(array('captain'), 'string', array('max'=>20)),
		);
	}
	
	public function labels(){
		return array(
			'id'=>'Id',
			'name'=>'兵制',
			'captain'=>'统兵官',
			'soldiers'=>'统领士兵数',
			'months'=>'获得军衔规则：月',
			'times'=>'获得军衔规则：累计次数',
			'continuous'=>'获得军衔规则：连续签到天数',
			'sort'=>'军衔高低（值越高表示军衔越高）',
		);
	}
	
	public function filters(){
		return array(
			'id'=>'intval',
			'name'=>'trim',
			'captain'=>'trim',
			'soldiers'=>'intval',
			'months'=>'intval',
			'times'=>'intval',
			'continuous'=>'intval',
			'sort'=>'intval',
		);
	}
}