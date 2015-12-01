<?php
namespace fay\models;

use fay\core\Model;

/**
 * 基于左右值的多树操作
 * 该模型针对一张表对应多棵树的数据结构，适用于无限极回复、评论等需求
 * 该模型不支持手工排序，后插入的记录永远在先插入记录后面出现
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
	 * @return MultiTree
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 创建一个节点
	 * @param string $model 表模型
	 * @param array $data 数据
	 * @param int $parent 父节点
	 */
	public function create($model, $data, $parent = 0){
		if($parent == 0){
			//插入根节点
			$node_id = \F::model($model)->insert(array_merge($data, array(
				'parent'=>$parent,
				'left_value'=>1,
				'right_value'=>2,
			)));
			//根节点是自己
			\F::model($model)->update(array(
				'root'=>$node_id,
			), $node_id);
		}else{
			//插入叶子节点
			$parent_node = \F::model($model)->find($parent, 'id,root,left_value,right_value');
			$root = $parent_node['root'] ? $parent_node['root'] : $parent_node['id'];
			if($parent_node['right_value'] - $parent_node['left_value'] == 1){
				//父节点是叶子节点
				\F::model($model)->inc(array(
					'left_value > '.$parent_node['left_value'],
					'root = ' . $root,
				), 'left_value', 2);
				\F::model($model)->inc(array(
					'right_value > '.$parent_node['left_value'],
					'root = ' . $root,
				), 'right_value', 2);
				$node_id = \F::model($model)->insert(array_merge($data, array(
					'parent'=>$parent,
					'left_value'=>$parent_node['left_value'] + 1,
					'right_value'=>$parent_node['left_value'] + 2,
					'root'=>$root,
				)));
			}else{
				//父节点非叶子节点，插入到最右侧
				$left_node = \F::model($model)->fetchRow(array(
					'parent = '.$parent,
				), 'left_value,right_value', 'id DESC');
				\F::model($model)->inc(array(
					'left_value > '.$left_node['right_value'],
					'root = ' . $root,
				), 'left_value', 2);
				\F::model($model)->inc(array(
					'right_value > '.$left_node['right_value'],
					'root = ' . $root,
				), 'right_value', 2);
				$node_id = \F::model($model)->insert(array_merge($data, array(
					'parent'=>$parent,
					'left_value'=>$left_node['right_value'] + 1,
					'right_value'=>$left_node['right_value'] + 2,
					'root'=>$root,
				)));
			}
		}
		return $node_id;
	}
	
	/**
	 * 软删除一个节点
	 * 软删除不会改变节点的parent，但是会修改left_value和right_value，在还原时可根据parent回复层级结构
	 * @param string $model 表模型
	 * @param int $id 节点ID
	 */
	public function delete($model, $id){
		
	}
	
	/**
	 * 还原一个节点
	 * @param string $model 表模型
	 * @param int $id 节点ID
	 */
	public function undelete($model, $id){
		
	}
	
	/**
	 * 删除一个节点
	 * 物理删除，其子节点会挂到其父节点上
	 * @param string $model 表模型
	 * @param int $id 节点ID
	 */
	public function remove($model, $id){
		
	}
}