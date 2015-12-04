<?php
namespace fay\models;

use fay\core\Model;
use fay\core\db\Expr;

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
 *  - deleted 删除标记
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
		//获取被删除节点
		$node = \F::model($model)->find($id, 'left_value,right_value');
		if($node['right_value'] - $node['left_value'] == 1){
			//被删除节点是叶子节点
			if($node['right_value'] != 2){
				//不是根节点（是根节点且是叶子节点的话，无需操作）
				//所有后续节点左右值-2
				\F::model($model)->inc(array(
					'right_value > '.$node['right_value'],
					'left_value > '.$node['right_value'],
				), array('left_value', 'right_value'), -2);
				//所有父节点右值-2
				\F::model($model)->inc(array(
					'right_value > '.$node['right_value'],
					'left_value < '.$node['left_value'],
				), 'right_value', -2);
			}
		}else{
			//被删除节点还有子节点，则将子节点设为根节点
			$children_nodes = \F::model($model)->fetchAll(array(
				'parent = ' . $id,
				'deleted = 0',
			));
			foreach($children_nodes as $cn){
				\F::model($model)->update(array(
					'root'=>$cn['id'],
					'left_value'=>new Expr('left_value - ' . ($cn['left_value'] - 1)),
					'right_value'=>new Expr('right_value - ' . ($cn['left_value'] - 1)),
				), array(
					'root = ' . $cn['root'],
					'left_value >= ' . $cn['left_value'],
					'right_value <= ' . $cn['right_value'],
				));
			}
		}
		//标记为已删除即可
		\F::model($model)->update(array(
			'deleted'=>1,
		), $id);
		
		return true;
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
		//获取被删除节点
		$node = \F::model($model)->find($id, 'left_value,right_value,parent');
		
		if($node['right_value'] - $node['left_value'] == 1){
			//被删除节点是叶子节点
			if($node['right_value'] != 2){
				//不是根节点（是根节点且是叶子节点的话，无需操作）
				//所有后续节点左右值-2
				\F::model($model)->inc(array(
					'right_value > '.$node['right_value'],
					'left_value > '.$node['right_value'],
				), array('left_value', 'right_value'), -2);
				//所有父节点右值-2
				\F::model($model)->inc(array(
					'right_value > '.$node['right_value'],
					'left_value < '.$node['left_value'],
				), 'right_value', -2);
			}
		}else{
			//所有子节点左右值-1
			\F::model($model)->update(array(
				'left_value'=>new Expr('left_value - 1'),
				'right_value'=>new Expr('right_value - 1'),
			), array(
				'left_value > '.$node['left_value'],
				'right_value < '.$node['right_value'],
			));
			//所有后续节点左右值-2
			\F::model($model)->update(array(
				'left_value'=>new Expr('left_value - 2'),
				'right_value'=>new Expr('right_value - 2'),
			), array(
				'right_value > '.$node['right_value'],
				'left_value > '.$node['right_value'],
			));
			//所有父节点
			\F::model($model)->update(array(
				'right_value'=>new Expr('right_value - 2'),
			), array(
				'right_value > '.$node['right_value'],
				'left_value < '.$node['left_value'],
			));
		}
		//删除当前节点
		\F::model($model)->delete($id);
		//将所有父节点为该节点的parent字段指向其parent
		\F::model($model)->update(array(
			'parent'=>$node['parent'],
		), 'parent = '.$id);
		
		return true;
	}
}