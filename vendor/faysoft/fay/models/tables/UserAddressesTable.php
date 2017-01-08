<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * User Addresses model
 * 
 * @property int $id
 * @property int $user_id
 * @property int $state
 * @property int $city
 * @property int $district
 * @property string $address
 * @property string $name
 * @property string $mobile
 * @property string $phone
 * @property string $zipcode
 * @property int $create_time
 * @property int $is_default
 */
class UserAddressesTable extends Table{
	protected $_name = 'user_addresses';
	
	/**
	 * @param string $class_name
	 * @return UserAddressesTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('state', 'city', 'district'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('address'), 'string', array('max'=>255)),
			array(array('name'), 'string', array('max'=>50)),
			array(array('phone', 'zipcode'), 'string', array('max'=>30)),
			array(array('is_default'), 'range', array('range'=>array(0, 1))),
			array(array('mobile'), 'mobile'),
			
			array(array('district', 'address', 'name'), 'required'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'user_id'=>'用户ID',
			'state'=>'所在省',
			'city'=>'所在市',
			'district'=>'所在区',
			'address'=>'详细地址',
			'name'=>'姓名',
			'mobile'=>'手机号码',
			'phone'=>'电话号码',
			'zipcode'=>'邮编',
			'create_time'=>'创建时间',
			'is_default'=>'默认收货地址',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'user_id'=>'intval',
			'state'=>'intval',
			'city'=>'intval',
			'district'=>'intval',
			'address'=>'trim',
			'name'=>'trim',
			'mobile'=>'trim',
			'phone'=>'trim',
			'zipcode'=>'trim',
			'is_default'=>'intval',
		);
	}
}