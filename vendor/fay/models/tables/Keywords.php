<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Keywords extends Table{
	protected $_name = 'keywords';
	
	/**
	 * @return Keywords
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('keyword'), 'string', array('max'=>50)),
			array(array('link'), 'string', array('max'=>500)),
			
			array(array('link', 'keyword'), 'required'),
			array('link', 'url'),
			array('keyword', 'unique', array('table'=>'keywords', 'field'=>'keyword', 'except'=>'id', 'ajax'=>array('admin/keyword/is-keyword-not-exist'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'keyword'=>'关键词',
			'link'=>'链接地址',
		);
	}

	public function filters(){
		return array(
			'keyword'=>'trim',
			'link'=>'trim',
		);
	}
}