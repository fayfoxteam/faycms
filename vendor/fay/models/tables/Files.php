<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Files extends Table{
	protected $_name = 'files';
	
	/**
	 * @return Files
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id', 'file_size', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('image_width', 'image_height'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('downloads'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('qiniu'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('cat_id'), 'int', array('min'=>0, 'max'=>255)),
			array(array('raw_name'), 'string', array('max'=>32)),
			array(array('file_ext'), 'string', array('max'=>10)),
			array(array('file_type'), 'string', array('max'=>30)),
			array(array('file_path', 'client_name'), 'string', array('max'=>255)),
			array(array('is_image'), 'range', array('range'=>array('0', '1'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'raw_name'=>'Raw Name',
			'file_ext'=>'File Ext',
			'file_size'=>'File Size',
			'file_type'=>'File Type',
			'file_path'=>'File Path',
			'client_name'=>'Client Name',
			'is_image'=>'Is Image',
			'image_width'=>'Image Width',
			'image_height'=>'Image Height',
			'upload_time'=>'Upload Time',
			'user_id'=>'User Id',
			'downloads'=>'Downloads',
			'cat_id'=>'Cat Id',
			'qiniu'=>'Qiniu',
		);
	}

	public function filters(){
		return array(
			'raw_name'=>'trim',
			'file_ext'=>'trim',
			'file_size'=>'intval',
			'file_type'=>'trim',
			'file_path'=>'trim',
			'client_name'=>'trim',
			'is_image'=>'intval',
			'image_width'=>'intval',
			'image_height'=>'intval',
			'upload_time'=>'trim',
			'user_id'=>'intval',
			'downloads'=>'intval',
			'cat_id'=>'intval',
			'qiniu'=>'intval',
		);
	}
}