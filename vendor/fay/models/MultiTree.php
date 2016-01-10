<?php
namespace fay\models;

use fay\core\Model;
use fay\core\db\Expr;
use fay\core\ErrorException;

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
	 */
	public function getTree($model, $field, $value, $count = 10, $offset = 0){
		//得到根节点
		$root_nodes = \F::model($model)->fetchCol('root', array(
			"{$field} = ?"=>$value,
			'left_value = 1',
		), false, $count, $offset);
		
		if($root_nodes){
			//搜索所有节点
			$nodes = \F::model($model)->fetchAll(array(
				'root IN (?)'=>$root_nodes,
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