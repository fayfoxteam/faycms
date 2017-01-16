<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Third party apps table model
 *
 * @property int $id Id
 * @property string $app_name 应用名称
 * @property string $app_code 应用编码，对应代码中的包名
 * @property string $app_id 从第三方平台申请到的应用ID
 * @property string $app_secret 从第三方平台申请到的应用密钥
 * @property string $configs 配置信息
 * @property int $create_time 创建时间
 * @property int $last_modified_time 最后更新时间
 */
class ThirdPartyAppsTable extends Table{
	protected $_name = 'third_party_apps';
	
	/**
	 * @param string $class_name
	 * @return ThirdPartyAppsTable
	 
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('app_name', 'app_code', 'app_id'), 'string', array('max'=>50)),
			array(array('app_secret'), 'string', array('max'=>100)),
		);
	}
	
	public function labels(){
		return array(
			'id'=>'Id',
			'app_name'=>'应用名称',
			'app_code'=>'应用编码，对应代码中的包名',
			'app_id'=>'从第三方平台申请到的应用ID',
			'app_secret'=>'从第三方平台申请到的应用密钥',
			'configs'=>'配置信息',
			'create_time'=>'创建时间',
			'last_modified_time'=>'最后更新时间',
		);
	}
	
	public function filters(){
		return array(
			'id'=>'intval',
			'app_name'=>'trim',
			'app_code'=>'trim',
			'app_id'=>'trim',
			'app_secret'=>'trim',
			'configs'=>'',
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