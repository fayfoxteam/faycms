<?php
namespace fay\models;

use fay\core\Model;
use fay\core\db\Expr;
use fay\core\Exception;

/**
 * 基于左右值的多树操作
 * 该模型针对一张表对应多棵树的数据结构，适用于无限极回复、评论等需求
 * 该模型不支持手工排序，后插入的记录永远在先插入记录后面出现
 * 该模型不做数据正确性验证
 * **关键字段**
 *  - id 节点ID
 *  - left_value 左值（正常节点左值从1开始，被删除的节点左右值都为0）
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
				//父节点非叶子节点，插入到最左侧
				\F::model($model)->inc(array(
					'root = ' . $parent_node['root'],
					'left_value > ' . $parent_node['left_value'],
				), 'left_value', 2);
				\F::model($model)->inc(array(
					'root = ' . $parent_node['root'],
					'right_value >= ' . $parent_node['left_value'],
				), 'right_value', 2);
				$node_id = \F::model($model)->insert(array_merge($data, array(
					'parent'=>$parent,
					'left_value'=>$parent_node['left_value'] + 1,
					'right_value'=>$parent_node['left_value'] + 2,
					'root'=>$parent_node['root'],
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
		$node = \F::model($model)->find($id, 'id,left_value,right_value,root');
		if(!$node){
			throw new Exception('指定节点不存在');
		}
		
		if($node['left_value'] == 0){
			//节点已经被删除，返回false
			return false;
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
		}else{
			//差值
			$diff = $node['right_value'] - $node['left_value'] + 1;
			
			//所有子节点平铺到根节点（子节点与其子孙保持父子关系）
			//孩子要先处理掉，先做差值的话就无法通过左右值判断孩子了
			$children = \F::model($model)->fetchAll(array(
				'parent = ' . $node['id'],
				'left_value != 0',
			));
			foreach($children as $c){
				\F::model($model)->update(array(
					'root'=>$c['id'],
					'left_value'=>new Expr('left_value - ' . ($c['left_value'] - 1)),
					'right_value'=>new Expr('right_value - ' . ($c['left_value'] - 1)),
				), array(
					'root = ' . $c['root'],
					'left_value >= ' . $c['left_value'],
					'right_value <= ' . $c['right_value'],
				));
			}
			
			//所有后续节点减去差值
			\F::model($model)->inc(array(
				'root = ' . $node['root'],
				'left_value > ' . $node['left_value'],
				'right_value > ' . $node['right_value'],
			), array(
				'left_value', 'right_value',
			), - $diff);
			//所有父节点的右节点减去差值
			\F::model($model)->inc(array(
				'root = ' . $node['root'],
				'left_value < ' . $node['left_value'],
				'right_value > ' . $node['right_value'],
			), 'right_value', - $diff);
		}
		//标记为已删除
		\F::model($model)->update(array(
			'left_value'=>0,
			'right_value'=>0,
		), $id);
		
		return true;
	}
	
	/**
	 * 还原一个节点
	 * @param string $model 表模型
	 * @param int $id 节点ID
	 */
	public function undelete($model, $id){
		//获取要还原的节点
		$node = \F::model($model)->find($id, 'id,left_value,right_value,parent');
		if(!$node){
			throw new Exception('指定节点不存在');
		}
		if($node['left_value']){
			//节点不在被删除状态，直接return false
			return false;
		}
		
		if($node['parent']){
			//有父节点，等于是先做一次带顺序的插入操作
			$parent_node = \F::model($model)->find($node['parent'], 'left_value,right_value,root');
			if(!$parent_node){
				throw new Exception('父节点不存在， 参数异常');
			}
			
			if($parent_node['left_value'] == 0){
				//如果父节点在被删除状态，该节点挂到根节点上
				\F::model($model)->update(array(
					'root'=>$node['id'],
					'left_value'=>1,
					'right_value'=>2,
				), $node['id']);
			}else if($parent_node['right_value'] - $parent_node['left_value'] == 1){
				//父节点本身是叶子节点，直接挂载
				\F::model($model)->inc(array(
					'root = ' . $parent_node['root'],
					'left_value > ' . $parent_node['left_value']
				), 'left_value', 2);
				\F::model($model)->inc(array(
					'root = ' . $parent_node['root'],
					'right_value > ' . $parent_node['left_value']
				), 'right_value', 2);
				\F::model($model)->update(array(
					'root'=>$parent_node['root'],
					'left_value'=>$parent_node['left_value'] + 1,
					'right_value'=>$parent_node['left_value'] + 2,
				), $node['id']);
			}else{
				//父节点非叶子节点
				//定位新插入节点的排序位置
				$left_node = \F::model($model)->fetchRow(array(
					'parent = ' . $node['parent'],
					'id > ' . $node['id'],
				), 'left_value,right_value', 'id ASC');
				
				if($left_node){
					//存在左节点
					\F::model($model)->inc(array(
						'root = ' . $left_node['root'],
						'left_value > ' . $left_node['right_value']
					), 'left_value', 2);
					\F::model($model)->inc(array(
						'root = ' . $left_node['root'],
						'right_value > ' . $left_node['right_value'],
					), 'right_value', 2);
					
					\F::model($model)->update(array(
						'root'=>$left_node['root'],
						'left_value'=>$left_node['right_value'] + 1,
						'right_value'=>$left_node['right_value'] + 2,
					), $node['id']);
				}else{
					//不存在左节点，即在孩子的最前面插入
					\F::model($model)->inc(array(
						'root = ' . $parent_node['root'],
						'left_value > ' . $parent_node['left_value'],
					), 'left_value', 2);
					\F::model($model)->inc(array(
						'root = ' . $parent_node['root'],
						'right_value > ' . $parent_node['left_value'],
					), 'right_value', 2);
					
					\F::model($model)->update(array(
						'root'=>$parent_node['root'],
						'left_value'=>$parent_node['left_value'] + 1,
						'right_value'=>$parent_node['left_value'] + 2,
					), $node['id']);
				}
			}
		}else{
			//本身就是根节点，直接还原
			\F::model($model)->update(array(
				'root'=>$node['id'],
				'left_value'=>1,
				'right_value'=>2,
			), $node['id']);
		}
		
		//更新孩子节点（如果存在的话，它们一定都被挂到根节点上了）
		$children = \F::model($model)->fetchAll(array(
			'parent = ' . $node['id'],
			'left_value > 0',
		), 'id,left_value,right_value', 'id ASC');
		if($children){
			$node = \F::model($model)->find($node['id'], 'id,root,left_value,right_value');//重新获取一遍，上面有改动
			
			//先循环一遍，确认父节点增量，如果先插入的话，就无法通过左右值确定父节点了
			$diff = 0;//后续及祖先节点增量
			foreach($children as $c){
				$diff += $c['right_value'];
			}
			//后续节点左右值加上增量
			\F::model($model)->inc(array(
				'root = ' . $node['root'],
				'left_value > ' . $node['left_value'],
				'right_value > ' . $node['right_value'],
			), array(
				'left_value', 'right_value'
			), $diff);
			//祖先节点（包含自己）右值加上增量
			\F::model($model)->inc(array(
				'root = ' . $node['root'],
				'left_value <= ' . $node['left_value'],
				'right_value >= ' . $node['right_value'],
			), 'right_value', $diff);
			
			//执行子节点插入
			$diff = $node['left_value'];//子节点增量
			foreach($children as $c){
				//依次挂到被还原的父节点上
				\F::model($model)->update(array(
					'root'=>$node['root'],
					'left_value'=>new Expr('left_value + ' . $diff),
					'right_value'=>new Expr('right_value + ' . $diff),
				), array(
					'root = '.$c['id'],
				));
				$diff += $c['right_value'];
			}
		}
		
		return true;
	}
	
	/**
	 * 删除一个节点
	 * 物理删除，其子节点会挂到其父节点上
	 * @param string $model 表模型
	 * @param int $id 节点ID
	 */
	public function remove($model, $id){
		//获取被删除节点
		$node = \F::model($model)->find($id, 'left_value,right_value,parent,root');
		if(!$node) return false;
		
		if(!$node['left_value']){
			//已经是已删除状态，直接移除该节点
			\F::model($model)->delete($id);
			
			//若存在子节点，将子节点的parent字段指向当前节点的父节点
			if(\F::model($model)->fetchRow(array(
				'parent = ' . $id
			))){
				\F::model($model)->update(array(
					'parent'=>$node['parent'],
				), 'parent = ' . $id);
			}
			
			return true;
		}else if($node['right_value'] - $node['left_value'] == 1){
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
			\F::model($model)->delete($id);
			
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
			), 'parent = ' . $id);
			
			//删除当前节点
			\F::model($model)->delete($id);
			
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
		$node = \F::model($model)->find($id, 'root');
		if(!$node) return false;
		
		/*
		 * 当该节点已经被delete的情况下，没办法通过左右值一次性删除所有子节点
		 * 同样的通过左右值也无法删除其已经被delete的子节点
		 * 所以在这里只能通过parent字段和root字段来递归删除，然后根据root字段来重建索引
		 */
		$child_ids = $this->getChildIdsByParentField($model, $id);
		if($child_ids){
			\F::model($model)->delete(array(
				'id IN (' . implode(',', $child_ids) . ')',
			));
			$this->buildIndex($model, $node['root']);
		}
		
		return true;
	}
	
	/**
	 * 根据parent字段，递归得到所有子节点id，连同当前节点id一起以一维数组方式返回
	 * 与通过左右值方式获取不同，该方法会连同被删除节点一起返回
	 */
	public function getChildIdsByParentField($model, $id){
		$children = \F::model($model)->fetchAll(array(
			'parent = ?'=>$id,
		), 'id');
		
		$child_ids = array((string)$id);//转一下类型，保证返回类型一致
		foreach($children as $c){
			$child_ids = array_merge($child_ids, $this->getChildIdsByParentField($model, $c['id']));
		}
		return $child_ids;
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
			'left_value > 0',//已删除的不管
			'parent = ?'=>$parent,
		), 'id', 'id DESC');
		foreach($nodes as $node){
			$children = \F::model($model)->fetchAll(array(
				'root = ?'=>$root,
				'left_value > 0',
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
	 */
	public function getTree($model, $field, $value, $count = 10, $offset = 0){
		//得到根节点
		$root_nodes = \F::model($model)->fetchCol('root', array(
			"{$field} = ?"=>$value,
			'left_value = 1',//已删除的不管
		), false, $count, $offset);
		
		if($root_nodes){
			//搜索所有节点
			$nodes = \F::model($model)->fetchAll(array(
				'root IN (?)'=>$root_nodes,
				'left_value > 0',
			), \F::model($model)->getFields(), 'root DESC, left_value ASC');
			
			//一棵一棵渲染
			$sub_tree = array();
			$last_root = $nodes[0]['root'];
			$tree = array();
			foreach($nodes as $n){
				if($last_root != $n['root'] && $sub_tree){
					$tree[] = $this->renderTree($sub_tree);
					$sub_tree = array();
					$last_root = $n['root'];
				}
				
				$sub_tree[] = $n;
			}
			$tree[] = $this->renderTree($sub_tree);
			return $tree;
		}else{
			return array();
		}
	}
	
	/**
	 * 根据left_value和right_value渲染出一个多维数组
	 * @param array $nodes
	 */
	public function renderTree($nodes, $parent = 0){
		if(empty($nodes)) return array();
		$level = 0;//下一根树枝要挂载的层级
		$current_level = 0;//当前层级
		$left = $nodes[0]['left_value'] - 1;//上一片叶子的左值
		$branch = array();//树枝
		$parent_node = null;//叶子前一级树枝
		$leaf = null;//叶子
		$tree = array();//树
		foreach($nodes as $n){
			if($n['left_value'] - $left == 1){
				//子节点
				if(empty($branch)){
					$branch[] = $n;
					$leaf = &$branch[0];
					$parent_node = &$branch;
				}else{
					$leaf['children'] = array($n);
					$parent_node = &$leaf;
					$leaf = &$leaf['children'][0];
				}
				$current_level++;
			}else if($n['left_value'] - $left == 2){
				//同级叶子
				if(isset($parent_node['children'])){
					$parent_node['children'][] = $n;
					$leaf = &$parent_node['children'][count($parent_node['children']) - 1];
				}else{
					//该树枝的根
					$parent_node[] = $n;
					$leaf = &$parent_node[count($parent_node) - 1];
				}
			}else{
				//当前树枝遍历完毕，转向父节点进行遍历
				$tree = $this->mountBranch($branch, $tree, $level);//将之前产生的树枝先挂到树上
				$level = $current_level - ($n['left_value'] - $left - 1);//下次挂在这个位置
				$current_level = $level + 1;
				$branch = array($n);//重置树枝
				$parent_node = &$branch;
				$leaf = &$branch[0];
			}
			$left = $n['left_value'];
		}
		$tree = $this->mountBranch($branch, $tree, $level);
		return $tree;
	}
	
	/**
	 * 将一根树枝挂载到指定树的指定层级的最右侧
	 * @param array $branch
	 * @param array $tree
	 * @param int $level
	 */
	private function mountBranch($branch, $tree, $level){
		if($level == 0){
			$tree = array_merge($tree, $branch);
		}else{
			$temp = &$tree[count($tree) - 1];//第一层的最后一个元素的引用
			for($i = 1; $i < $level; $i++){
				$temp = &$temp['children'][count($temp['children']) - 1];
			}
			$temp['children'] = array_merge($temp['children'], $branch);
		}
		return $tree;
	}
}