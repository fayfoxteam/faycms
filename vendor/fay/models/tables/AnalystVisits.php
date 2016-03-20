<?php
namespace fay\models\tables;

use fay\core\db\Table;

class AnalystVisits extends Table{
	protected $_name = 'analyst_visits';
	
	/**
	 * @return AnalystVisits
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('id', 'mac', 'create_time'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('user_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('site'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('hour', 'views'), 'int', array('min'=>0, 'max'=>255)),
			array(array('refer', 'url', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'), 'string', array('max'=>255)),
			array(array('short_url'), 'string', array('max'=>6)),
			array(array('trackid'), 'string', array('max'=>30)),
			
			array(array('url'), 'url'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'mac'=>'Mac',
			'ip_int'=>'IP',
			'refer'=>'Refer',
			'url'=>'Url',
			'short_url'=>'Short Url',
			'trackid'=>'Trackid',
			'user_id'=>'User Id',
			'create_time'=>'创建时间',
			'create_date'=>'Create Date',
			'hour'=>'Hour',
			'site'=>'Site',
			'views'=>'Views',
			'HTTP_CLIENT_IP'=>'HTTP CLIENT IP',
			'HTTP_X_FORWARDED_FOR'=>'HTTP X FORWARDED FOR',
			'REMOTE_ADDR'=>'REMOTE ADDR',
		);
	}

	public function filters(){
		return array(
			'mac'=>'intval',
			'ip_int'=>'intval',
			'refer'=>'trim',
			'url'=>'trim',
			'short_url'=>'trim',
			'trackid'=>'trim',
			'user_id'=>'intval',
			'create_time'=>'',
			'create_date'=>'',
			'hour'=>'intval',
			'site'=>'intval',
			'views'=>'intval',
			'HTTP_CLIENT_IP'=>'trim',
			'HTTP_X_FORWARDED_FOR'=>'trim',
			'REMOTE_ADDR'=>'trim',
		);
	}
}