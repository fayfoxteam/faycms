<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Post Comments model
 * 
 * @property int $id Id
 * @property int $post_id 文章ID
 * @property int $user_id 用户ID
 * @property string $content 内容
 * @property int $parent 父ID
 * @property int $root 根评论ID
 * @property int $create_time 创建时间
 * @property int $status 状态
 * @property int $deleted 删除标记
 * @property int $is_terminal 是否为叶子节点
 * @property int $is_real 是否真实用户
 */
class PostComments extends Table{
	/**
	 * 状态-待审核
	 */
	const STATUS_PENDING = 1;
	/**
	 * 状态-通过审核
	 */
	const STATUS_APPROVED = 2;
	/**
	 * 状态-通过审核
	 */
	const STATUS_UNAPPROVED = 3;
	
	protected $_name = 'post_comments';
	
	/**
	 * @return PostComments
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id', 'post_id', 'user_id', 'parent', 'root'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('deleted', 'is_terminal', 'is_real'), 'range', array('range'=>array(0, 1))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'post_id'=>'文章ID',
			'user_id'=>'用户ID',
			'content'=>'内容',
			'parent'=>'父ID',
			'root'=>'根评论ID',
			'create_time'=>'创建时间',
			'status'=>'状态',
			'deleted'=>'删除标记',
			'is_terminal'=>'是否为叶子节点',
			'is_real'=>'是否真实用户',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'post_id'=>'intval',
			'user_id'=>'intval',
			'content'=>'',
			'parent'=>'intval',
			'root'=>'intval',
			'create_time'=>'',
			'status'=>'intval',
			'deleted'=>'intval',
			'is_terminal'=>'intval',
			'is_real'=>'intval',
		);
	}
}