<?php
namespace ncp\models\tables;

use fay\core\db\Table;

class TourRoute extends Table{
	protected $_name = 'ncp_tour_route';
	
	/**
	 * @return TourRoute
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id', 'post_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			
			array('route', 'required'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'post_id'=>'Post Id',
			'route'=>'路线攻略',
			'sort'=>'排序值',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'post_id'=>'intval',
			'route'=>'',
			'sort'=>'intval',
		);
	}
}