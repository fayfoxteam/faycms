<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * 付款方式
 *
 * @property int $id Id
 * @property string $code 包名
 * @property string $name 名称
 * @property string $description 描述
 * @property int $enabled 是否启用
 * @property string $config 配置信息JSON
 * @property int $create_time 创建时间
 * @property int $last_modified_time 最后编辑时间
 */
class PaymentsTable extends Table{
	protected $_name = 'payments';
	
	public static $codes = array(
		'weixin:jsapi'=>'微信JsApi支付'
	);
	
	/**
	 * @param string $class_name
	 * @return PaymentsTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('enabled'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('id'), 'int', array('min'=>0, 'max'=>255)),
			array(array('code'), 'string', array('max'=>20)),
			array(array('name'), 'string', array('max'=>50)),
			array(array('description'), 'string', array('max'=>500)),
		);
	}
	
	public function labels(){
		return array(
			'id'=>'Id',
			'code'=>'包名',
			'name'=>'名称',
			'description'=>'描述',
			'enabled'=>'是否启用',
			'config'=>'配置信息JSON',
			'create_time'=>'创建时间',
			'last_modified_time'=>'最后编辑时间',
		);
	}
	
	public function filters(){
		return array(
			'id'=>'intval',
			'code'=>'trim',
			'name'=>'trim',
			'description'=>'trim',
			'enabled'=>'intval',
			'config'=>'',
		);
	}
	
	public function getNotWritableFields($scene){
		switch($scene){
			case 'insert':
				return array('id');
				break;
			case 'update':
				return array(
					'id', 'create_time', 'deleted'
				);
				break;
			default:
				return array();
		}
	}
}