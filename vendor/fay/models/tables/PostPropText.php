<?php
namespace fay\models\tables;

use fay\core\db\Table;

class PostPropText extends Table{
	protected $_name = 'post_prop_text';
	protected $_primary = array('post_id', 'prop_id');
	
	/**
	 * @return PostPropText
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('post_id', 'prop_id'), 'int', array('min'=>0, 'max'=>4294967295)),
		);
	}

	public function labels(){
		return array(
			'post_id'=>'Post Id',
			'prop_id'=>'Prop Id',
			'content'=>'Content',
		);
	}

	public function filters(){
		return array(
			'post_id'=>'intval',
			'prop_id'=>'intval',
			'content'=>'',
		);
	}
}