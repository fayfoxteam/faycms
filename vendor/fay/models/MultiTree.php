<?php
namespace fay\models;

use fay\core\Model;
use fay\core\db\Expr;
use fay\core\ErrorException;
use fay\helpers\FieldHelper;
use fay\helpers\ArrayHelper;

/**
 * 基于左右值的多树操作
 * 该模型针对一张表对应多棵树的数据结构，适用于无限极回复、评论等需求。
 * 该模型不支持手工排序，后插入的记录永远在先插入记录后面出现。
 * 该模型正序或者倒序关系并不明确，因为评论是正序或者倒序都是合理的需求，左右值的作用只是用于获取子节点、判断节点是否为叶子节点。
 * 因为左右值并不用于保证节点直接的顺序，所以构造树的时候用parent节点来构造。
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
			if($parent_node['right_value'] - $parent_node['left_value'] == 1){
				//父节点是叶子节点
				\F::model($model)->inc(array(
					'root = ' . $parent_node['root'],
					'left_value > ' . $parent_node['left_value'],
				), 'left_value', 2);
				\F::model($model)->inc(array(
					'root = ' . $parent_node['root'],
					'right_value > ' . $parent_node['left_value'],
				), 'right_value', 2);
				$node_id = \F::model($model)->insert(array_merge($data, array(
					'parent'=>$parent,
					'left_value'=>$parent_node['left_value'] + 1,
					'right_value'=>$parent_node['left_value'] + 2,
					'root'=>$parent_node['root'],
				)));
			}else{
				//父节点非叶子节点，插入到最右侧（因为留言系统一般回复都是按时间正序排列的）
				$left_node = \F::model($model)->fetchRow(array(
					'parent = ' . $parent,
				), 'left_value,right_value', 'left_value DESC');
				\F::model($model)->inc('left_value > ' . $left_node['right_value'], 'left_value', 2);
				\F::model($model)->inc('right_value > ' . $left_node['right_value'], 'right_value', 2);
				$node_id = \F::model($model)->insert(array_merge($data, array(
					'parent'=>$parent,
					'root'=>$parent_node['root'],
					'left_value'=>$left_node['right_value'] + 1,
					'right_value'=>$left_node['right_value'] + 2,
				)));
			}
		}
		return $node_id;
	}
	
	/**
	 * 删除一个节点
	 * 物理删除，其子节点会挂到其父节点上
	 * @param string $model 表模型
	 * @param int|array $id 节点ID或包含id,left_value,right_value,parent,root节点信息的数组
	 */
	public function remove($model, $node){
		//获取被删除节点
		if(!is_array($node)){
			$node = \F::model($model)->find($node, 'id,left_value,right_value,parent,root');
		}
		if(!$node){
			throw new ErrorException('节点不存在', 'node-not-exist');
		}
		
		if($node['right_value'] - $node['left_value'] == 1){
			//被删除节点是叶子节点
			if($node['right_value'] != 2){
				//不是根节点（是根节点且是叶子节点的话，无需操作）
				//所有后续节点左右值-2
				\F::model($model)->inc(array(
					'root = ' . $node['root'],
					'right_value > ' . $node['right_value'],
					'left_value > ' . $node['right_value'],
				), array('left_value', 'right_value'), -2);
				//所有父节点右值-2
				\F::model($model)->inc(array(
					'root = ' . $node['root'],
					'right_value > ' . $node['right_value'],
					'left_value < ' . $node['left_value'],
				), 'right_value', -2);
			}
			
			//删除当前节点
			\F::model($model)->delete($node['id']);
			
			return true;
		}else{
			//所有子节点左右值-1
			\F::model($model)->update(array(
				'left_value'=>new Expr('left_value - 1'),
				'right_value'=>new Expr('right_value - 1'),
			), array(
				'root = ' . $node['root'],
				'left_value > ' . $node['left_value'],
				'right_value < ' . $node['right_value'],
			));
			//所有后续节点左右值-2
			\F::model($model)->update(array(
				'left_value'=>new Expr('left_value - 2'),
				'right_value'=>new Expr('right_value - 2'),
			), array(
				'root = ' . $node['root'],
				'left_value > ' . $node['right_value'],
				'right_value > ' . $node['right_value'],
			));
			\F::model($model)->inc(array(
				'root = ' . $node['root'],
				'left_value < ' . $node['left_value'],
				'right_value > ' . $node['right_value'],
			), array(
				'left_value', 'right_value'
			), -2);
			//所有父节点
			\F::model($model)->inc(array(
				'root = ' . $node['root'],
				'left_value < ' . $node['left_value'],
				'right_value > ' . $node['right_value'],
			), 'right_value', -2);
			
			//将所有父节点为该节点的parent字段指向其parent
			\F::model($model)->update(array(
				'parent'=>$node['parent'],
			), 'parent = ' . $node['id']);
			
			//删除当前节点
			\F::model($model)->delete($node['id']);
			
			return true;
		}
	}
	
	/**
	 * 删除一个节点，及其所有子节点
	 * @param string $model 表模型
	 * @param int $id 节点ID
	 */
	public function removeAll($model, $id){
		//获取被删除节点
		$node = \F::model($model)->find($id, 'left_value,right_value,parent');
		if(!$node){
			throw new ErrorException('节点不存在', 'node-not-exist');
		}
		
		//删除所有树枝节点
		\F::model($model)->delete(array(
			'left_value >= ' . $node['left_value'],
			'right_value <= ' . $node['right_value'],
		));
		
		//差值
		$diff = $node['right_value'] - $node['left_value'] + 1;
		//所有后续节点减去差值
		\F::model($model)->update(array(
			'left_value'=>new Expr('left_value - ' . $diff),
			'right_value'=>new Expr('right_value - ' . $diff),
		), array(
			'left_value > ' . $node['left_value'],
			'right_value > ' . $node['right_value'],
		));
		//所有父节点的右节点减去差值
		\F::model($model)->update(array(
			'right_value'=>new Expr('right_value - ' . $diff),
		), array(
			'left_value < ' . $node['left_value'],
			'right_value > ' . $node['right_value'],
		));
		return true;
	}
	
	/**
	 * 根据root重建一棵树的索引
	 * @param string $model
	 * @param int $parent
	 * @param int $start_num
	 * @param array $nodes 递归的时候，直接传入子节点，而不需要再搜一次
	 */
	public function buildIndex($model, $root, $parent = 0, $start_num = 0, $nodes = null){
		$nodes || $nodes = \F::model($model)->fetchAll(array(
			'root = ?'=>$root,
			'parent = ?'=>$parent,
		), 'id', 'id DESC');
		foreach($nodes as $node){
			$children = \F::model($model)->fetchAll(array(
				'root = ?'=>$root,
				'parent = ?'=>$node['id'],
			), 'id', 'id DESC');
			if($children){
				//有孩子，先记录左节点，右节点待定
				$left = ++$start_num;
				$start_num = $this->buildIndex($model, $root, $node['id'], $start_num, $children);
				\F::model($model)->update(array(
					'left_value'=>$left,
					'right_value'=>++$start_num,
				), $node['id']);
			}else{
				//已经是叶子节点，直接记录左右节点
				\F::model($model)->update(array(
					'left_value'=>++$start_num,
					'right_value'=>++$start_num,
				), $node['id']);
			}
		}
		return $start_num;
	}
	
	/**
	 * 以树的方式返回
	 * @param string $model
	 * @param string $field 字段名，例如：post_id
	 * @param int $value 字段值，例如：文章ID
	 * @param int $count 返回根记录数（回复有多少返回多少，不分页）
	 * @param int $offset 偏移量（根据页码在调用前算好
	 * @param string $fields 可指定返回字段（虽然把user等字段放这里会让model看起来不纯，但是性能上会好很多）
	 *  - 无前缀系列可指定$model表返回字段，若未指定，默认为*
	 *  - user.*系列可指定作者信息，格式参照\fay\models\User::get()
	 * @param array $conditions 附加条件（例如审核状态等与树结构本身无关的条件）
	 * @param string $order 排序条件
	 */
	public function getTree($model, $field, $value, $count = 10, $offset = 0, $fields = '*', $conditions = array(), $order = 'root DESC, left_value ASC'){
		//解析$fields
		$fields = FieldHelper::process($fields, 'tree');
		if(empty($fields['tree']) || in_array('*', $fields)){
			$fields['tree'] = \F::model($model)->getFields();
		}
		$tree_fields = $fields['tree'];
		//一些需要用到，但未指定返回的字段特殊处理下
		foreach(array('root', 'left_value', 'right_value', 'parent') as $key){
			if(!in_array($key, $tree_fields)){
				$tree_fields[] = $key;
			}
		}
		if(!empty($fields['user']) && !in_array('user_id', $tree_fields)){
			$tree_fields[] = 'user_id';
		}
		
		//得到根节点
		$root_nodes = \F::model($model)->fetchCol('root', array(
			"{$field} = ?"=>$value,
			'left_value = 1',
		) + $conditions, $order, $count, $offset);
		
		if($root_nodes){
			//搜索所有节点
			$nodes = \F::model($model)->fetchAll(array(
				'root IN (?)'=>$root_nodes,
			) + $conditions, $tree_fields, $order);
			
			//像user这种附加信息，可以一次性获取以提升性能
			$extra = array();
			if(!empty($fields['user'])){
				$extra['users'] = User::model()->mget(array_unique(ArrayHelper::column($nodes, 'user_id')), implode(',', $fields['user']));
			}
			
			//一棵一棵渲染
			$sub_tree = array();
			$last_root = $nodes[0]['root'];
			$tree = array();
			foreach($nodes as $n){
				if($last_root != $n['root'] && $sub_tree){
					$tree[] = $this->renderTree($sub_tree, $fields, 0, $extra);
					$sub_tree = array();
					$last_root = $n['root'];
				}
				
				$sub_tree[] = $n;
			}
			$tree[] = $this->renderTree($sub_tree, $fields, 0, $extra);
			return $tree;
		}else{
			return array();
		}
	}
	
	/**
	 * 根据parent字段渲染出一个多维数组
	 * （因为$nodes不会包含软删除数据，所以利用left_value和right_value是构造不出tree的，不连续）
	 * @param array $nodes
	 * @param array $fields
	 * @param int $parent
	 * @param array $extra
	 */
	public function renderTree($nodes, $fields, $parent = 0, $extra = array()){
		$tree = array();
		if(empty($nodes)) return $tree;
		foreach($nodes as $k => $n){
			if($n['parent'] == $parent){
				$node = array();
				//只返回需要返回的字段
				foreach($n as $key => $val){
					if(in_array($key, $fields['tree'])){
						$node['data'][$key] = $val;
					}
				}
				
				if(!empty($extra['users'])){
					//获取user信息
					$node['user'] = $extra['users'][$n['user_id']];
				}
				
				if($n['right_value'] - $n['left_value'] != 1){
					//非叶子，获取子树
					$node['children'] = $this->renderTree($nodes, $fields, $n['id'], $extra);
				}
				
				$tree[] = $node;
				unset($nodes[$k]);
			}
		}
		return $tree;
	}
}