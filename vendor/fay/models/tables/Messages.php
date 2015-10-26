<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Messages extends Table{
	/**
	 * 状态-未审核
	 */
	const STATUS_PENDING = 0;
	
	/**
	 * 状态-已审核
	 */
	const STATUS_APPROVED = 1;
	
	/**
	 * 状态-未通过审核
	 */
	const STATUS_UNAPPROVED = 2;

	/**
	 * 类型-文章评论
	 * @var int
	 */
	const TYPE_POST_COMMENT = 1;
	
	/**
	 * 类型-留言
	 * @var int
	 */
	const TYPE_USER_MESSAGE = 2;


	protected $_name = 'messages';
	
	/**
	 * @return Messages
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id', 'user_id', 'target', 'parent', 'root', 'create_time'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('type'), 'int', array('min'=>0, 'max'=>255)),
			array(array('deleted', 'is_terminal'), 'range', array('range'=>array(0, 1))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'user_id'=>'用户id',
			'target'=>'目标',
			'content'=>'评论内容',
			'parent'=>'Parent',
			'root'=>'Root',
			'type'=>'Type',
			'create_time'=>'Create Time',
			'status'=>'审核状态 ',
			'deleted'=>'Deleted',
			'is_terminal'=>'判断是否为叶子节点',
		);
	}

	public function filters(){
		return array(
			'user_id'=>'intval',
			'target'=>'intval',
			'content'=>'',
			'parent'=>'intval',
			'root'=>'intval',
			'type'=>'intval',
			'create_time'=>'',
			'status'=>'intval',
			'deleted'=>'intval',
			'is_terminal'=>'intval',
		);
	}
}