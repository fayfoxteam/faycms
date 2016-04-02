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
 * @property int $sort 排序值
 * @property int $status 状态
 * @property int $deleted 删除标记
 * @property string $address 地址
 */
class Feeds extends Table{
	/**
	 * 动态状态-草稿
	 */
	const STATUS_DRAFT = 0;
	
	/**
	 * 动态状态-待审核
	 */
	const STATUS_PENDING = 1;
	
	/**
	 * 动态状态-通过审核
	 */
	const STATUS_APPROVED = 2;
	
	/**
	 * 动态状态-未通过审核
	 */
	const STATUS_UNAPPROVED = 3;
	
	protected $_name = 'feeds';
	
	/**
	 * @return Feeds
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('address'), 'string', array('max'=>500)),
			array(array('deleted'), 'range', array('range'=>array(0, 1))),
			array(array('publish_time', 'timeline'), 'datetime'),

			array(array('status'), 'range', array('range'=>array(self::STATUS_DRAFT, self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_UNAPPROVED))),
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
			'timeline'=>'时间轴',
			'status'=>'状态',
			'deleted'=>'删除标记',
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
			'timeline'=>'',
			'status'=>'intval',
			'deleted'=>'intval',
			'address'=>'trim',
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