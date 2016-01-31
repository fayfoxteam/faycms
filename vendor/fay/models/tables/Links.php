<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Links model
 *
 * @property int $id Id
 * @property string $title 标题
 * @property string $description 描述
 * @property string $url 网址
 * @property int $visiable 可见
 * @property int $user_id 添加者
 * @property string $target 打开方式
 * @property int $create_time 创建时间
 * @property int $last_modified_time 最后修改时间
 * @property int $sort 排序值
 * @property int $logo Logo
 * @property int $cat_id 分类
 */
class Links extends Table{
	protected $_name = 'links';
	
	/**
	 * @return Links
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('user_id', 'logo'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('id', 'cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('visiable'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title', 'description', 'url'), 'string', array('max'=>255)),
			array(array('target'), 'string', array('max'=>25)),
			
			array('url', 'url'),
			array(array('title', 'url'), 'required')
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'title'=>'标题',
			'description'=>'描述',
			'url'=>'网址',
			'visiable'=>'可见',
			'user_id'=>'用户ID',
			'target'=>'打开方式',
			'create_time'=>'创建时间',
			'last_modified_time'=>'最后修改时间',
			'sort'=>'排序值',
			'logo'=>'Logo',
			'cat_id'=>'分类',
		);
	}

	public function filters(){
		return array(
			'title'=>'trim',
			'description'=>'trim',
			'url'=>'trim',
			'visiable'=>'intval',
			'user_id'=>'intval',
			'target'=>'trim',
			'create_time'=>'',
			'last_modified_time'=>'',
			'sort'=>'intval',
			'logo'=>'intval',
			'cat_id'=>'intval',
		);
	}
}