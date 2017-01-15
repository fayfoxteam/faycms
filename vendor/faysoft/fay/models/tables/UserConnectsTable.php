<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * User connects table model
 *
 * @property int $id Id
 * @property int $user_id 用户ID
 * @property int $third_party_app_id 第三方应用ID
 * @property string $open_id 第三方应用对外ID
 * @property int $create_time 创建时间
 */
class UserConnectsTable extends Table{
	protected $_name = 'user_connects';
	
	/**
	 * @param string $class_name
	 * @return UserConnectsTable
	 
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('third_party_app_id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('open_id'), 'string', array('max'=>50)),
		);
	}
	
	public function labels(){
		return array(
			'id'=>'Id',
			'user_id'=>'用户ID',
			'third_party_app_id'=>'第三方应用ID',
			'open_id'=>'第三方应用对外ID',
			'create_time'=>'创建时间',
		);
	}
	
	public function filters(){
		return array(
			'id'=>'intval',
			'user_id'=>'intval',
			'third_party_app_id'=>'intval',
			'open_id'=>'trim',
		);
	}
	
	public function getNotWritableFields($scene){
		switch($scene){
			case 'insert':
				return array('id');
				break;
			case 'update':
				return array(
					'id', 'create_time',
				);
				break;
			default:
				return array();
		}
	}
}