<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Feed Extra model
 * 
 * @property int $feed_id 动态ID
 * @property int $ip_int IP
 * @property float $longitude 经度
 * @property float $latitude 纬度
 */
class FeedExtra extends Table{
	protected $_name = 'feed_extra';
	protected $_primary = 'feed_id';
	
	/**
	 * @return FeedExtra
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('feed_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array('longitude', 'float', array('length'=>9, 'decimal'=>6, 'min'=>-180, 'max'=>180)),
			array('latitude', 'float', array('length'=>9, 'decimal'=>6, 'min'=>-90, 'max'=>90)),
		);
	}

	public function labels(){
		return array(
			'feed_id'=>'动态ID',
			'ip_int'=>'IP',
			'longitude'=>'经度',
			'latitude'=>'纬度',
		);
	}

	public function filters(){
		return array(
			'feed_id'=>'intval',
			'longitude'=>'floatval',
			'latitude'=>'floatval',
		);
	}
	
	public function getNotWritableFields($scene){
		switch($scene){
			case 'insert':
				return array();
			case 'update':
			default:
				return array(
					'feed_id', 'ip_int'
				);
		}
	}
}