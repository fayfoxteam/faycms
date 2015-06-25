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
	 * 根据别名获取一个菜单项
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
	 * 根据父节点，返回导航树
	 * 若不指定父节点或指定为null，返回用户自定义菜单
	 * @param int|string|null $parent 父节点ID或别名
	 *  - 若为数字，视为ID获取菜单；
	 *  - 若为字符串，视为别名获取菜单；
	 * @param $real_link 返回渲染后的真实url
	 * @param $only_enabled 若为true，仅返回启用的菜单集
	 * @return array
	 */
	public function getTree($parent = null, $real_link = true, $only_enabled = true){
		if($parent === null){
			return Tree::model()->getTree('fay\models\tables\Menus', Menus::ITEM_USER_MENU);
		}else if(is_numeric($parent)){
			return $this->getTreeByParentId($parent, $real_link, $only_enabled);
		}else{
			return $this->getTreeByParentAlias($parent, $real_link, $only_enabled);
		}
	}
	
	/**
	 * 根据父节点别名，返回导航树
	 * 若不指定别名，返回用户自定义菜单
	 */
	public function getTreeByParentAlias($alias = null, $real_link = true, $only_enabled = true){
		if($alias === null){
			return Tree::model()->getTree('fay\models\tables\Menus', Menus::ITEM_USER_MENU);
		}else{
			$root = $this->getByAlias($alias, 'id');
			if($root){
				return $this->getTreeByParentId($root['id'], $real_link, $only_enabled);
			}else{
				return array();
			}
		}
	}
	
	/**
	 * 根据父节点ID，返回导航树
	 * 若不指定ID，返回用户自定义菜单
	 */
	public function getTreeByParentId($id = null, $real_link = true, $only_enabled = true){
		if($id == null){
			return Tree::model()->getTree('fay\models\tables\Menus', Menus::ITEM_USER_MENU);
		}
		
		$menu = Tree::model()->getTree('fay\models\tables\Menus', $id);
		
		//无法在搜索树的时候就删除关闭的菜单，在这里再循环移除
		if($only_enabled){
			$menu = $this->removeDisabledItems($menu);
		}
		
		if($real_link){
			return $this->renderLink($menu);
		}else{
			return $menu;
		}
	}
	
	/**
	 * 递归移除未启用的菜单项（若父节点未启用，其子节点会一并被移除）
	 * @param array $menu
	 * @return array
	 */
	public function removeDisabledItems(&$menu){
		foreach($menu as $k => &$m){
			if(!$m['enabled']){
				unset($menu[$k]);
			}else{
				if(!empty($m['children'])){
					//子节点
					$children = $this->removeDisabledItems($m['children']);
					if($children){
						$m['children'] = $children;
					}else{
						//子节点全部不启用，删除children项
						unset($m['children']);
					}
				}
			}
		}
		return $menu;
	}
	
	/**
	 * 将link替换为真实url
	 */
	public function renderLink($menu){
		foreach($menu as &$m){
			$m['link'] = str_replace('{$base_url}', \F::config()->get('base_url'), $m['link']);
			if(isset($m['children'])){
				$m['children'] = $this->renderLink($m['children']);
			}
		}
		return $menu;
	}
}