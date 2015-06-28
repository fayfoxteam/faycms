<?php
namespace fay\models\tables;

use fay\core\db\Table;

class AnalystCaches extends Table{
	protected $_name = 'analyst_caches';
	
	/**
	 * @return AnalystCaches
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('site', 'pv', 'uv', 'ip', 'new_visitors'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('hour'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('bounce_rate'), 'float', array('length'=>5, 'decimal'=>2)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'date'=>'Date',
			'hour'=>'Hour',
			'site'=>'Site',
			'pv'=>'Pv',
			'uv'=>'Uv',
			'ip'=>'Ip',
			'new_visitors'=>'New Visitors',
			'bounce_rate'=>'Bounce Rate',
		);
	}

	public function filters(){
		return array(
			'date'=>'',
			'hour'=>'intval',
			'site'=>'intval',
			'pv'=>'intval',
			'uv'=>'intval',
			'ip'=>'intval',
			'new_visitors'=>'intval',
			'bounce_rate'=>'floatval',
		);
	}
}