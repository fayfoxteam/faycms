<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Analyst Sites table model
 *
 * @property int $id Id
 * @property string $title 站点名称
 * @property string $description 描述
 * @property int $deleted Deleted
 */
class AnalystSitesTable extends Table{
	protected $_name = 'analyst_sites';
	
	/**
	 * @param string $class_name
	 * @return AnalystSitesTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('title', 'description'), 'string', array('max'=>255)),
			array(array('deleted'), 'range', array('range'=>array(0, 1))),
			
			array('title', 'required'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'title'=>'站点名称',
			'description'=>'描述',
			'deleted'=>'Deleted',
		);
	}

	public function filters(){
		return array(
			'title'=>'trim',
			'description'=>'trim',
			'deleted'=>'intval',
		);
	}
}