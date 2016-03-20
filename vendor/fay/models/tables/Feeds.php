<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Feeds model
 * 
 * @property int $id Id
 * @property int $user_id 用户ID
 * @property string $content 内容
 * @property int $create_time 创建时间
 * @property int $last_modified_time 最后修改时间
 * @property int $publish_time 发布时间
 * @property string $publish_date 发布日期
 * @property int $status 状态
 * @property int $deleted 删除标记
 * @property int $ip_int IP
 * @property float $longitude 经度
 * @property float $latitude 纬度
 * @property string $address 地址
 */
class Feeds extends Table{
	protected $_name = 'feeds';
	
	/**
	 * @return Feeds
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('id', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('address'), 'string', array('max'=>500)),
			array(array('longitude', 'latitude'), 'float', array('length'=>9, 'decimal'=>6)),
			array(array('deleted'), 'range', array('range'=>array(0, 1))),
			array(array('publish_time'), 'datetime'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'user_id'=>'用户ID',
			'content'=>'内容',
			'create_time'=>'创建时间',
			'last_modified_time'=>'最后修改时间',
			'publish_time'=>'发布时间',
			'publish_date'=>'发布日期',
			'status'=>'状态',
			'deleted'=>'删除标记',
			'ip_int'=>'IP',
			'longitude'=>'经度',
			'latitude'=>'纬度',
			'address'=>'地址',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'user_id'=>'intval',
			'content'=>'',
			'publish_time'=>'trim',
			'publish_date'=>'',
			'status'=>'intval',
			'deleted'=>'intval',
			'longitude'=>'floatval',
			'latitude'=>'floatval',
			'address'=>'trim',
		);
	}
}