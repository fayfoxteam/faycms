<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Categories;
use fay\helpers\String;
use fay\helpers\ArrayHelper;

class Category extends Model{
	/**
	 * @return Category
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 索引记录
	 * @param int $parent
	 * @param int $start_num
	 */
	public function buildIndex($parent = 0, $start_num = 1){
		Tree::model()->buildIndex('fay\models\tables\Categories', $parent, $start_num);
	}
	
	/**
	 * 根据分类别名获取一个分类信息
	 * @param string $alias
	 * @param string $fields
	 * @param int|string|array $root 若指定root，则只搜索root下的分类
	 *  - 若为数字，视为分类ID
	 *  - 若为字符串，视为分类别名
	 *  - 若为数组，则必须包含left_value和right_value
	 */
	public function getByAlias($alias, $fields = '*', $root = null){
		if($root !== null && !is_array($root)){
			if(String::isInt($root)){
				$root = $this->getById($root, 'left_value,right_value');
			}else{
				$root = $this->getByAlias($root, 'left_value,right_value');
			}
		}
		
		$conditions = array(
			'alias = ?'=>$alias,
		);
		if($root){
			$conditions['left_value >= ?'] = $root['left_value'];
			$conditions['right_value <= ?'] = $root['right_value'];
		}
		return Categories::model()->fetchRow($conditions, $fields);
	}
	
	/**
	 * 根据分类ID获取一个分类信息
	 * @param string $id 单个分类ID
	 * @param string $fields
	 * @param int|string|array $root 若指定root，则只搜索root下的分类
	 *  - 若为数字，视为分类ID
	 *  - 若为字符串，视为分类别名
	 *  - 若为数组，则必须包含left_value和right_value
	 */
	public function getById($id, $fields = '*', $root = null){
		if($root !== null && !is_array($root)){
			if(String::isInt($root)){
				$root = $this->getById($root, 'left_value,right_value');
			}else{
				$root = $this->getByAlias($root, 'left_value,right_value');
			}
		}
		
		$conditions = array(
			'id = ?'=>$id,
		);
		if($root){
			$conditions['left_value >= ?'] = $root['left_value'];
			$conditions['right_value <= ?'] = $root['right_value'];
		}
		return Categories::model()->fetchRow($conditions, $fields);
	}
	
	/**
	 * 根据分类ID串获取多个分类信息
	 * @param string $ids 多个分类ID（数组或者逗号分隔），返回数组会与传入id顺序一致并以id为数组键
	 * @param string $fields 可选categories表字段
	 * @param int|string|array $root 若指定root，则只搜索root下的分类
	 *  - 若为数字，视为分类ID
	 *  - 若为字符串，视为分类别名
	 *  - 若为数组，则必须包含left_value和right_value
	 */
	public function mget($ids, $fields = '*', $root = null){
		if(!is_array($ids)){
			$ids = explode(',', $ids);
		}
		$fields = Categories::model()->formatFields($fields);
		$remove_id = false;//最受是否删除id字段
		if(!in_array('id', $fields)){
			//id必须搜出，若为指定，则先插入id字段，到后面再unset掉
			$fields[] = 'id';
			$remove_id = true;
		}
		if($root !== null && !is_array($root)){
			if(String::isInt($root)){
				$root = $this->getById($root, 'left_value,right_value');
			}else{
				$root = $this->getByAlias($root, 'left_value,right_value');
			}
		}
		
		$conditions = array(
			'id IN (?)'=>$ids,
		);
		if($root){
			$conditions['left_value >= ?'] = $root['left_value'];
			$conditions['right_value <= ?'] = $root['right_value'];
		}
		$cats = Categories::model()->fetchAll($conditions, $fields);
		//根据传入ID顺序返回
		$return = array();
		foreach($cats as $c){
			$return[$c['id']] = $c;
		}
		return $return;
	}
	
	/**
	 * 根据父节点，获取其所有子节点，返回二维数组（非树形）<br>
	 * 若不指定别名，返回整张表
	 * @param int|string $parent 父节点ID或别名
	 *  - 若为数字，视为分类ID获取分类；
	 *  - 若为字符串，视为分类别名获取分类；
	 * @param string $fields
	 * @return array
	 */
	public function getChildren($parent = null, $fields = '!seo_title,seo_keywords,seo_description,is_system', $order = 'sort'){
		if($parent === null){
			return Categories::model()->fetchAll(array(), $fields, $order);
		}else if(String::isInt($parent)){
			return $this->getChildrenByParentId($parent, $fields, $order);
		}else{
			return $this->getChildrenByParentAlias($parent, $fields, $order);
		}
	}
	
	/**
	 * 根据父节点别名，获取其所有子节点，返回二维数组（非树形）
	 * 若不指定别名，返回整张表
	 * @param string $alias
	 * @param string $fields
	 * @return array
	 */
	public function getChildrenByParentAlias($alias = null, $fields = '!seo_title,seo_keywords,seo_description,is_system', $order = 'sort'){
		if($alias === null){
			return Categories::model()->fetchAll(array(), $fields, $order);
		}else{
			$node = $this->getByAlias($alias, 'left_value,right_value');
			if($node){
				return Categories::model()->fetchAll(array(
					'left_value > '.$node['left_value'],
					'right_value < '.$node['right_value'],
				), $fields, $order);
			}else{
				return array();
			}
		}
	}
	
	/**
	 * 根据父节点ID，获取其所有子节点，返回二维数组（非树形）
	 * 若不指定别名，返回整张表
	 * @param string $alias
	 * @param string $fields
	 * @return array
	 */
	public function getChildrenByParentId($id = 0, $fields = '!seo_title,seo_keywords,seo_description,is_system', $order = 'sort'){
		if($id == 0){
			return Categories::model()->fetchAll(array(), $fields, $order);
		}else{
			$node = $this->get($id, 'left_value,right_value');
			if($node){
				return Categories::model()->fetchAll(array(
					'left_value > '.$node['left_value'],
					'right_value < '.$node['right_value'],
				), $fields, $order);
			}else{
				return array();
			}
		}
	}
	
	/**
	 * 根据父节点，获取所有子节点的ID，以一维数组方式返回
	 * 若不指定$parent，返回整张表
	 * @param int|string $parent
	 *  - 若为数字，视为分类ID获取分类；
	 *  - 若为字符串，视为分类别名获取分类；
	 */
	public function getChildIds($parent = null){
		return ArrayHelper::column($this->getChildren($parent, 'id', 'id'), 'id');
	}
	
	/**
	 * 根据父节点，获取分类树
	 * 若不指定$parent或指定为null，返回整张表
	 * @param int|string $parent 父节点ID或别名
	 *  - 若为数字，视为分类ID获取分类；
	 *  - 若为字符串，视为分类别名获取分类；
	 * @return array
	 */
	public function getTree($parent = null){
		if($parent === null){
			return Tree::model()->getTree('fay\models\tables\Categories');
		}else if(String::isInt($parent)){
			return $this->getTreeByParentId($parent);
		}else{
			return $this->getTreeByParentAlias($parent);
		}
	}
	
	/**
	 * 根据父节点别名，获取分类树
	 * 若不指定别名，返回整张表
	 * @param string $alias
	 * @return array
	 */
	public function getTreeByParentAlias($alias = null){
		if($alias === null){
			return Tree::model()->getTree('fay\models\tables\Categories');
		}else{
			$node = $this->getByAlias($alias, 'id');
			if($node){
				return Tree::model()->getTree('fay\models\tables\Categories', $node['id']);
			}else{
				return array();
			}
		}
	}
	
	/**
	 * 根据父节点ID，获取分类树
	 * 若不指定$id或$id为0，返回整张表
	 * @param int $id
	 * @param string $fields 返回的字段
	 * @return array
	 */
	public function getTreeByParentId($id = 0, $fields = '!seo_title,seo_keywords,seo_description,is_system'){
		return Tree::model()->getTree('fay\models\tables\Categories', $id, $fields);
	}
	
	/**
	 * 根据父节点，获取其下一级节点
	 * @param int|string $parent 父节点ID或别名
	 *  - 若为数字，视为分类ID获取分类；
	 *  - 若为字符串，视为分类别名获取分类；
	 * @param string $fields 返回字段
	 * @param string $order 排序规则
	 */
	public function getNextLevel($parent, $fields = '*', $order = 'sort, id'){
		if(String::isInt($parent)){
			return $this->getNextLevelByParentId($parent, $fields, $order);
		}else{
			return $this->getNextLevelByParentAlias($parent, $fields, $order);
		}
	}
	
	/**
	 * 根据父节点别名，获取其下一级节点
	 * @param string $alias 父节点别名
	 * @param string $fields 返回字段
	 * @param string $order 排序规则
	 */
	public function getNextLevelByParentAlias($alias, $fields = '*', $order = 'sort, id'){
		$node = $this->getByAlias($alias, 'id');
		if($node){
			return Categories::model()->fetchAll(array(
				'parent = ?'=>$node['id'],
			), $fields, $order);
		}else{
			return array();
		}
	}
	
	/**
	 * 根据父节点ID，获取其下一级节点
	 * @param int $id 父节点ID
	 * @param string $fields 返回字段
	 * @param string $order 排序规则
	 */
	public function getNextLevelByParentId($id, $fields = '*', $order = 'sort, id'){
		return Categories::model()->fetchAll(array(
			'parent = ?'=>$id,
		), $fields, $order);
	}
	
	/**
	 * 获取一个或多个分类。
	 * @param int|string $cats
	 *  - 若为数字，视为分类ID获取分类（返回一维数组）；
	 *  - 若为字符串，视为分类别名获取分类（返回一维数组）；
	 *  - 若是数组，循环调用自己获取多个分类（数组项可以是数字也可以是字符串，返回二维数组）；
	 * @param string $fields
	 * @param int|string|array $root 若指定root，则只搜索root下的分类
	 *  - 若为数字，视为分类ID
	 *  - 若为字符串，视为分类别名
	 *  - 若为数组，则必须包含left_value和right_value
	 */
	public function get($cats, $fields = '*', $root = null){
		if($root !== null && !is_array($root)){
			if(String::isInt($root)){
				$root = $this->getById($root, 'left_value,right_value');
			}else{
				$root = $this->getByAlias($root, 'left_value,right_value');
			}
		}
		
		if(String::isInt($cats)){
			return $this->getById($cats, $fields, $root);
		}else{
			return $this->getByAlias($cats, $fields, $root);
		}
	}
	
	/**
	 * 修改一条记录的sort值，并修改左右值
	 * @param int $id
	 * @param int $sort
	 */
	public function sort($id, $sort){
		Tree::model()->sort('fay\models\tables\Categories', $id, $sort);
	}
	
	/**
	 * 创建一个节点
	 * @param int $parent
	 * @param int $sort
	 * @param array $data
	 */
	public function create($parent, $sort = 100, $data = array()){
		return Tree::model()->create('fay\models\tables\Categories', $parent, $sort, $data);
	}
	
	/**
	 * 删除一个节点，其子节点将被挂载到父节点
	 * @param int $id
	 */
	public function remove($id){
		return Tree::model()->remove('fay\models\tables\Categories', $id);
	}
	
	/**
	 * 删除一个节点，及其所有子节点
	 * @param int $id
	 */
	public function removeAll($id){
		return Tree::model()->removeAll('fay\models\tables\Categories', $id);
	}
	
	/**
	 * 更新一个节点
	 * @param int $parent
	 * @param int $sort
	 * @param array $data
	 */
	public function update($id, $data, $sort = null, $parent = null){
		return Tree::model()->update('fay\models\tables\Categories', $id, $data, $sort, $parent);
	}
	
	/**
	 * 判断$cat1是否为$cat2的子节点（是同一节点也返回true）
	 * @param int|string|array $cat1
	 *  - 若为数字，视为分类ID获取分类；
	 *  - 若为字符串，视为分类别名获取分类；
	 *  - 若是数组，必须包含left_value和right_value
	 * @param int|string|string|array $cat2
	 *  - 若为数字，视为分类ID获取分类；
	 *  - 若为字符串，视为分类别名获取分类；
	 *  - 若是数组，必须包含left_value和right_value
	 */
	public function isChild($cat1, $cat2){
		if(!is_array($cat1)){
			$cat1 = $this->get($cat1, 'left_value,right_value');
		}
		if(!is_array($cat2)){
			$cat2 = $this->get($cat2, 'left_value,right_value');
		}
		
		return Tree::model()->isChild('fay\models\tables\Categories', $cat1, $cat2);
	}
	
	/**
	 * 获取祖谱
	 * 若root为null，则会一直追溯到根节点，否则追溯到root为止
	 * cat和root都可以是：{
	 *  - 数字:代表分类ID;
	 *  - 字符串:分类别名;
	 *  - 数组:分类数组（节约服务器资源，少一次数据库搜索。必须包含left_value和right_value字段）
	 * } 
	 * @param int|string|array $cat
	 * @param int|string|array $root
	 */
	public function getParentPath($cat, $root = null){
		if(!is_array($cat)){
			$cat = $this->get($cat, 'left_value,right_value');
		}
		
		if($root && !is_array($root)){
			$root = $this->get($root, 'left_value,right_value');
		}
		
		return Tree::model()->getParentIds('fay\models\tables\Categories', $cat, $root);
	}
	
	/**
	 * 获取指定节点的祖先节点的ID，以一位数组方式返回（包含指定节点ID）
	 * 若root为null，则会一直追溯到根节点，否则追溯到root为止
	 * cat和root都可以是：{
	 *  - 数字:代表分类ID;
	 *  - 字符串:分类别名;
	 *  - 数组:分类数组（节约服务器资源，少一次数据库搜索。必须包含left_value和right_value字段）
	 * } 
	 * @param int|string|array $cat
	 * @param int|string|array $root
	 */
	public function getParentIds($cat, $root = null){
		if(!is_array($cat)){
			$cat = $this->get($cat, 'left_value,right_value');
		}
		
		if($root && !is_array($root)){
			$root = $this->get($root, 'left_value,right_value');
		}
		
		return Tree::model()->getParentIds('fay\models\tables\Categories', $cat, $root);
	}
	
	/**
	 * 根据别名返回ID。
	 * 若指定别名不存在，返回false
	 * @param string $alias
	 */
	public function getIdByAlias($alias){
		$cat = $this->get($alias, 'id');
		if($cat){
			return $cat['id'];
		}else{
			return false;
		}
	}
}