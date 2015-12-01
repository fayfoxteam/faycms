<?php
namespace fay\models;

use fay\core\Model;

/**
 * 基于左右值的多树操作
 * 该模型针对一张表对应多棵树的数据结构，适用于无限极回复、评论等需求
 * 该模型不做数据正确性验证
 * **关键字段**
 *  - id 节点ID
 *  - left_value 左值
 *  - right_value 右值
 *  - parent 父节点ID
 *  - root 根节点。多树模型，需要有个根节点来标识树之间的关系
 */
class MultiTree extends Model{
	/**
	 * 创建一个节点
	 * @param string $model 表模型
	 * @param array $data 数据
	 * @param int $parent 父节点
	 */
	public function create($model, $data, $parent = 0){
		
	}
	
	public function delete(){
		
	}
	
	public function remove(){
		
	}
}