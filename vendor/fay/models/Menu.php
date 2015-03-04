<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Menus;

class Menu extends Model{
	/**
	 * @param string $className
	 * @return Menu
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 索引记录
	 * @param int $parent
	 * @param int $start_num
	 */
	public function buildIndex($parent = 0, $start_num = 1){
		Tree::model()->buildIndex('fay\models\tables\Menus', $parent, $start_num);
	}
	
	/**
	 * 创建一个节点
	 * @param int $parent
	 * @param int $sort
	 * @param array $data
	 */
	public function create($parent, $sort = 100, $data = array()){
		return Tree::model()->create('fay\models\tables\Menus', $parent, $sort, $data);
	}
	
	/**
	 * 删除一个节点，其子节点将被挂载到父节点
	 * @param int $id
	 */
	public function remove($id){
		return Tree::model()->remove('fay\models\tables\Menus', $id);
	}
	
	/**
	 * 删除一个节点，及其所有子节点
	 * @param int $id
	 */
	public function removeAll($id){
		return Tree::model()->removeAll('fay\models\tables\Menus', $id);
	}
	
	/**
	 * 更新一个节点
	 * @param int $parent
	 * @param int $sort
	 * @param array $data
	 */
	public function update($id, $data, $sort = null, $parent = null){
		return Tree::model()->edit('fay\models\tables\Menus', $id, $data, $sort, $parent);
	}
	
	/**
	 * 修改一条记录的sort值，并修改左右值
	 * @param int $id
	 * @param int $sort
	 */
	public function sort($id, $sort){
		Tree::model()->sort('fay\models\tables\Menus', $id, $sort);
	}
	
	/**
	 * 根据别名获取一个分类信息
	 * @param string $alias
	 * @param string $fields
	 * @return array
	 */
	public function getByAlias($alias, $fields = 'id,parent,alias,title,sort'){
		return Menus::model()->fetchRow(array(
			'alias = ?'=>$alias,
		), $fields);
	}
	
	/**
	 * 根据父节点别名，获取其所有子节点，返回二维数组<br>
	 * 若不指定别名，返回整张表
	 */
	public function getTree($alias = null, $real_link = true){
		if($alias === null){
			return Tree::model()->getTree('fay\models\tables\Menus');
		}else{
			$node = $this->getByAlias($alias, 'id');
			if($node){
				$menu = Tree::model()->getTree('fay\models\tables\Menus', $node['id']);
				if($real_link){
					return $this->renderLink($menu);
				}else{
					return $menu;
				}
			}else{
				return array();
			}
		}
	}
	
	/**
	 * 将link替换为真实url
	 */
	public function renderLink($menu){
		foreach($menu as &$m){
			$m['link'] = str_replace('{$base_url}', $this->config('base_url'), $m['link']);
			if(isset($m['children'])){
				$m['children'] = $this->renderLink($m['children']);
			}
		}
		return $menu;
	}
}