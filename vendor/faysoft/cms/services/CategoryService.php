<?php
namespace cms\services;

use fay\core\ErrorException;
use fay\core\Loader;
use fay\helpers\FieldHelper;
use cms\models\tables\CategoriesTable;
use fay\helpers\StringHelper;
use fay\helpers\ArrayHelper;
use fay\models\TreeModel;

class CategoryService extends TreeModel{
    /**
     * @see Tree::$model
     */
    protected $model = 'cms\models\tables\CategoriesTable';

    /**
     * id与别名对应关系
     * 相当于是缓存
     * @var array
     */
    private $id_alias_map = array();

    /**
     * 别名与id对应关系
     * 相当于是缓存
     * @var array
     */
    private $alias_id_map = array();
    
    /**
     * @param string $class_name
     * @return CategoryService
     */
    public static function service($class_name = __CLASS__){
        return Loader::singleton($class_name);
    }
    
    /**
     * 根据分类别名获取一个分类信息
     * @param string $alias
     * @param string $fields
     * @param int|string|array $root 若指定root，则只搜索root下的分类
     *  - 若为数字，视为分类ID
     *  - 若为字符串，视为分类别名
     *  - 若为数组，则必须包含left_value和right_value
     * @return array|bool
     */
    public function getByAlias($alias, $fields = '*', $root = null){
        $fields = FieldHelper::parse($fields, null, CategoriesTable::model()->getFields());
        
        if($root !== null && !is_array($root)){
            if(StringHelper::isInt($root)){
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
        return CategoriesTable::model()->fetchRow($conditions, $fields['fields']);
    }
    
    /**
     * 根据分类ID获取一个分类信息
     * @param string $id 单个分类ID
     * @param string $fields
     * @param int|string|array $root 若指定root，则只搜索root下的分类
     *  - 若为数字，视为分类ID
     *  - 若为字符串，视为分类别名
     *  - 若为数组，则必须包含left_value和right_value
     * @return array|bool
     */
    public function getById($id, $fields = '*', $root = null){
        $fields = FieldHelper::parse($fields, null, CategoriesTable::model()->getFields());
        
        if($root !== null && !is_array($root)){
            if(StringHelper::isInt($root)){
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
        return CategoriesTable::model()->fetchRow($conditions, $fields['fields']);
    }
    
    /**
     * 根据分类ID串获取多个分类信息
     * @param string $ids 多个分类ID（数组或者逗号分隔），返回数组会与传入id顺序一致并以id为数组键
     * @param string $fields 可选categories表字段
     * @param int|string|array $root 若指定root，则只搜索root下的分类
     *  - 若为数字，视为分类ID
     *  - 若为字符串，视为分类别名
     *  - 若为数组，则必须包含left_value和right_value
     * @return array
     */
    public function mget($ids, $fields = '*', $root = null){
        if(!is_array($ids)){
            $ids = explode(',', $ids);
        }
        
        $fields = FieldHelper::parse($fields);
        
        $table_fields = CategoriesTable::model()->formatFields($fields['fields']);
        $remove_id = false;//最受是否删除id字段
        if(!in_array('id', $table_fields)){
            //id必须搜出，若为指定，则先插入id字段，到后面再unset掉
            $table_fields[] = 'id';
            $remove_id = true;
        }
        if($root !== null && !is_array($root)){
            if(StringHelper::isInt($root)){
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
        $cats = CategoriesTable::model()->fetchAll($conditions, $table_fields);
        //根据传入ID顺序返回
        $return = array();
        foreach($cats as $c){
            $return[$c['id']] = $c;
            if($remove_id){
                unset($return[$c['id']]['id']);
            }
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
     * @param string $order
     * @return array
     */
    public function getChildren($parent = null, $fields = '!seo_title,seo_keywords,seo_description,is_system', $order = 'left_value'){
        if($parent === null){
            return CategoriesTable::model()->fetchAll(array(), $fields, $order);
        }else if(StringHelper::isInt($parent)){
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
     * @param string $order
     * @return array
     */
    public function getChildrenByParentAlias($alias = null, $fields = '!seo_title,seo_keywords,seo_description,is_system', $order = 'sort'){
        if($alias === null){
            return CategoriesTable::model()->fetchAll(array(), $fields, $order);
        }else{
            $node = $this->getByAlias($alias, 'left_value,right_value');
            if($node){
                return CategoriesTable::model()->fetchAll(array(
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
     * @param int $id
     * @param string $fields
     * @param string $order
     * @return array
     */
    public function getChildrenByParentId($id = 0, $fields = '!seo_title,seo_keywords,seo_description,is_system', $order = 'sort'){
        if($id == 0){
            return CategoriesTable::model()->fetchAll(array(), $fields, $order);
        }else{
            $node = $this->get($id, 'left_value,right_value');
            if($node){
                return CategoriesTable::model()->fetchAll(array(
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
     * @return array
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
     * @param string $fields
     * @return array
     */
    public function getTree($parent = null, $fields = '!seo_title,seo_keywords,seo_description,is_system'){
        if($parent === null){
            return parent::getTree(0, $fields);
        }else if(StringHelper::isInt($parent)){
            return $this->getTreeByParentId($parent, $fields);
        }else{
            return $this->getTreeByParentAlias($parent, $fields);
        }
    }
    
    /**
     * 根据父节点别名，获取分类树
     * 若不指定别名，返回整张表
     * @param string $alias
     * @param string $fields
     * @return array
     */
    public function getTreeByParentAlias($alias = null, $fields = '!seo_title,seo_keywords,seo_description,is_system'){
        if($alias === null){
            return parent::getTree(0, $fields);
        }else{
            $node = $this->getByAlias($alias, 'id');
            if($node){
                return parent::getTree($node['id'], $fields);
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
        return parent::getTree($id, $fields);
    }
    
    /**
     * 根据父节点，获取其下一级节点
     * @param int|string $parent 父节点ID或别名
     *  - 若为数字，视为分类ID获取分类；
     *  - 若为字符串，视为分类别名获取分类；
     * @param string $fields 返回字段
     * @param string $order 排序规则
     * @return array
     */
    public function getNextLevel($parent, $fields = '*', $order = 'sort, id'){
        $fields = FieldHelper::parse($fields, null, CategoriesTable::model()->getFields());
        
        if(StringHelper::isInt($parent)){
            return $this->getNextLevelByParentId($parent, $fields['fields'], $order);
        }else{
            return $this->getNextLevelByParentAlias($parent, $fields['fields'], $order);
        }
    }
    
    /**
     * 根据父节点别名，获取其下一级节点
     * @param string $alias 父节点别名
     * @param string $fields 返回字段
     * @param string $order 排序规则
     * @return array
     */
    public function getNextLevelByParentAlias($alias, $fields = '*', $order = 'sort, id'){
        $fields = FieldHelper::parse($fields, null, CategoriesTable::model()->getFields());
        
        $node = $this->getByAlias($alias, 'id');
        if($node){
            return CategoriesTable::model()->fetchAll(array(
                'parent = ?'=>$node['id'],
            ), $fields['fields'], $order);
        }else{
            return array();
        }
    }
    
    /**
     * 根据父节点ID，获取其下一级节点
     * @param int $id 父节点ID
     * @param string $fields 返回字段
     * @param string $order 排序规则
     * @return array
     */
    public function getNextLevelByParentId($id, $fields = '*', $order = 'sort, id'){
        $fields = FieldHelper::parse($fields, null, CategoriesTable::model()->getFields());
        
        return CategoriesTable::model()->fetchAll(array(
            'parent = ?'=>$id,
        ), $fields['fields'], $order);
    }
    
    /**
     * 获取一个或多个分类。
     * @param int|string $cat
     *  - 若为数字，视为分类ID获取分类（返回一维数组）；
     *  - 若为字符串，视为分类别名获取分类（返回一维数组）；
     * @param string $fields
     * @param int|string|array $root 若指定root，则只搜索root下的分类
     *  - 若为数字，视为分类ID
     *  - 若为字符串，视为分类别名
     *  - 若为数组，则必须包含left_value和right_value
     * @return array|bool
     */
    public function get($cat, $fields = '*', $root = null){
        $fields = FieldHelper::parse($fields, null, CategoriesTable::model()->getFields());
        
        if($root !== null && !is_array($root)){
            if(StringHelper::isInt($root)){
                $root = $this->getById($root, 'left_value,right_value');
            }else{
                $root = $this->getByAlias($root, 'left_value,right_value');
            }
        }
        
        if(StringHelper::isInt($cat)){
            return $this->getById($cat, $fields['fields'], $root);
        }else{
            return $this->getByAlias($cat, $fields['fields'], $root);
        }
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
     * @return bool
     */
    public function isChild($cat1, $cat2){
        if(!is_array($cat1)){
            $cat1 = $this->get($cat1, 'left_value,right_value');
        }
        if(!is_array($cat2)){
            $cat2 = $this->get($cat2, 'left_value,right_value');
        }
        
        return parent::isChild($cat1, $cat2);
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
     * @return array
     */
    public function getParentPath($cat, $root = null){
        if(!is_array($cat)){
            $cat = $this->get($cat, 'left_value,right_value');
        }
        
        if($root && !is_array($root)){
            $root = $this->get($root, 'left_value,right_value');
        }
        
        return parent::getParentIds($cat, $root);
    }
    
    /**
     * 获取指定节点的祖先节点的ID，以一位数组方式返回（包含指定节点ID）
     * 若root为null，则会一直追溯到根节点，否则追溯到root为止
     * cat和root都可以是
     *  - 数字:代表分类ID;
     *  - 字符串:分类别名;
     *  - 数组:分类数组（节约服务器资源，少一次数据库搜索。必须包含left_value和right_value字段）
     * @param int|string|array $cat
     * @param int|string|array $root
     * @return array
     */
    public function getParentIds($cat, $root = null){
        if(!is_array($cat)){
            $cat = $this->get($cat, 'left_value,right_value');
        }
        
        if($root && !is_array($root)){
            $root = $this->get($root, 'left_value,right_value');
        }
        
        return parent::getParentIds($cat, $root);
    }
    
    /**
     * 根据别名返回ID。
     * 若指定别名不存在，返回false
     * @param string $alias
     * @return int|false
     */
    public function getIdByAlias($alias){
        if(!isset($this->alias_id_map[$alias])){
            $cat = $this->get($alias, 'id');
            if($cat){
                $this->alias_id_map[$alias] = $cat['id'];
            }else{
                $this->alias_id_map[$alias] = false;
            }
        }
        
        return $this->alias_id_map[$alias];
    }

    /**
     * 根据ID返回别名。
     * 若指定ID不存在，返回false
     * @param int $id
     * @return string|false
     */
    public function getAliasById($id){
        if(!isset($this->id_alias_map[$id])){
            $cat = $this->get($id, 'alias');
            if($cat){
                $this->id_alias_map[$id] = $cat['alias'];
            }else{
                $this->id_alias_map[$id] = false;
            }
        }
        
        return $this->id_alias_map[$id];
    }
    
    /**
     * 递增一个或多个指定分类的计数
     * @param array|int $cat_ids
     * @param int $value 增量，默认为1，可以是负数
     * @return int
     */
    public function incr($cat_ids, $value = 1){
        if(!$cat_ids){
            return 0;
        }
        if(!is_array($cat_ids)){
            $cat_ids = explode(',', $cat_ids);
        }
        
        return CategoriesTable::model()->incr(array(
            'id IN (?)'=>$cat_ids,
        ), 'count', $value);
    }
    
    /**
     * 递减一个或多个指定分类的计数
     * @param array|int $cat_ids
     * @param int $value 增量，默认为1，正数表示递减
     * @return int
     */
    public function decr($cat_ids, $value = 1){
        return $this->incr($cat_ids, -$value);
    }
    
    /**
     * @param int $cat_id
     * @param int|string|array $root 若指定root，则只搜索root下的分类
     * @return bool
     */
    public function isIdExist($cat_id, $root){
        return !!$this->getById($cat_id, 'id', $root);
    }
    
    /**
     * 判断指定分类是否是叶子节点
     * @param int|string $cat
     * @return bool
     */
    public function isTerminal($cat){
        if(!is_array($cat)){
            $cat = $this->get($cat, 'left_value,right_value');
        }
        return ($cat['right_value'] - $cat['left_value']) == 1;
    }
    
    /**
     * @see CategoryService::isTerminal()
     * @param int|string $cat
     * @return bool
     */
    public function hasChildren($cat){
        return $this->isTerminal($cat);
    }
    
    /**
     * 获取指定分类的平级分类
     * @param int|string|array $cat
     *  - 若为数字，视为分类ID获取分类
     *  - 若为字符串，视为分类别名获取分类
     *  - 若是数组，必须包含parent字段
     * @param string $fields
     * @param string $order
     * @return array
     */
    public function getSibling($cat, $fields = '*', $order = 'sort, id'){
        $fields = FieldHelper::parse($fields, null, CategoriesTable::model()->getFields());
    
        if(StringHelper::isInt($cat)){
            return $this->getSiblingById($cat, $fields['fields'], $order);
        }else if(is_array($cat)){
            return $this->getSiblingByArray($cat, $fields['fields'], $order);
        }else{
            return $this->getSiblingByAlias($cat, $fields['fields'], $order);
        }
    }
    
    /**
     * 获取指定分类的平级分类
     * @param int $cat 分类ID
     * @param string $fields
     * @param string $order
     * @return array
     */
    public function getSiblingById($cat, $fields = '*', $order = 'sort, id'){
        $node = $this->getById($cat, 'parent');
        if($node){
            return $this->getSiblingByParentId($node['parent'], $fields, $order);
        }else{
            return array();
        }
    }
    
    /**
     * 获取指定分类的平级分类
     * @param string $cat 分类别名
     * @param string $fields
     * @param string $order
     * @return array
     */
    public function getSiblingByAlias($cat, $fields = '*', $order = 'sort, id'){
        $node = $this->getByAlias($cat, 'parent');
        if($node){
            return $this->getSiblingByParentId($node['parent'], $fields, $order);
        }else{
            return array();
        }
    }
    
    /**
     * 获取指定分类的平级分类
     * @param array $cat 至少包含parent字段的分类信息数组
     * @param string $fields
     * @param string $order
     * @return array
     * @throws ErrorException
     */
    public function getSiblingByArray($cat, $fields = '*', $order = 'sort, id'){
        if(!isset($cat['parent'])){
            throw new ErrorException('::' . __CLASS__ . __FUNCTION__ . '$cat参数必须包含parent字段');
        }
        
        return $this->getSiblingByParentId($cat['parent'], $fields, $order);
    }
    
    /**
     * 获取相同父节点ID的分类
     * @param int $parent_id 父节点ID
     * @param string $fields
     * @param string $order
     * @return array
     */
    public function getSiblingByParentId($parent_id, $fields = '*', $order = 'sort, id'){
        if(!$parent_id){
            //不允许返回根分类
            return array();
        }
        
        $fields = FieldHelper::parse($fields, null, CategoriesTable::model()->getFields());
        
        return CategoriesTable::model()->fetchAll(array(
            'parent = ?'=>$parent_id,
        ), $fields['fields'], $order);
    }
}