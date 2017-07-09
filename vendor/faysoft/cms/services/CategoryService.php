<?php
namespace cms\services;

use cms\models\tables\CategoriesTable;
use fay\core\ErrorException;
use fay\core\Exception;
use fay\core\Loader;
use fay\helpers\FieldsHelper;
use fay\helpers\NumberHelper;
use fay\helpers\StringHelper;
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
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
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
        $fields = new FieldsHelper($fields, 'category', CategoriesTable::model()->getFields());
        
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
        return CategoriesTable::model()->fetchRow($conditions, $fields->getFields());
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
        $fields = new FieldsHelper($fields, 'category', CategoriesTable::model()->getFields());
        
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
        return CategoriesTable::model()->fetchRow($conditions, $fields->getFields());
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
        
        $fields = new FieldsHelper($fields, 'category');
        
        $table_fields = CategoriesTable::model()->formatFields($fields->getFields());
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
     * @throws ErrorException
     * @throws Exception
     */
    public function getChildren($parent = null, $fields = '!seo_title,seo_keywords,seo_description,is_system', $order = 'left_value'){
        if($parent === null){
            //为null，则返回节点
            return parent::getChildren(0, $fields, $order);
        }else if(NumberHelper::isInt($parent)){
            //是数字，视为父节点ID
            return parent::getChildren($parent, $fields, $order);
        }else if(is_string($parent)){
            //是字符串，视为别名
            $node = $this->get($parent, 'left_value,right_value');
            if(!$node){
                throw new Exception("指定分类别名[{$parent}]不存在");
            }
            return parent::getChildren($node, $fields);
        }else{
            throw new ErrorException('无法识别的父节点格式: ' . serialize($parent));
        }
    }

    /**
     * 根据父节点，获取分类树
     * 若不指定$parent或指定为null，返回整张表
     * @param int|string $parent 父节点ID或别名
     *  - 若为数字，视为分类ID获取分类；
     *  - 若为字符串，视为分类别名获取分类；
     * @param string $fields
     * @return array
     * @throws ErrorException
     * @throws Exception
     */
    public function getTree($parent = 0, $fields = '!seo_title,seo_keywords,seo_description,is_system'){
        if($parent != '0' && (NumberHelper::isInt($parent) || is_string($parent))){
            $parent = $this->getOrFail($parent, 'id,left_value,right_value');
        }
        
        return parent::getTree($parent, $fields);
    }

    /**
     * 根据父节点，获取其下一级节点
     * @param int $cat
     * @param string $fields 返回字段
     * @param string $order 排序规则
     * @return array
     * @throws ErrorException
     * @throws Exception
     * @internal param int|string $parent 父节点ID或别名
     *  - 若为数字，视为分类ID获取分类；
     *  - 若为字符串，视为分类别名获取分类；
     */
    public function getNextLevel($cat, $fields = '*', $order = 'sort, id'){
        $fields = new FieldsHelper($fields, 'category', CategoriesTable::model()->getFields());
        
        if(StringHelper::isInt($cat)){
            return parent::getNextLevel($cat, $fields->getFields(), $order);
        }else if(is_string($cat)){
            $id = $this->getIdByAlias($cat);
            if(!$id){
                throw new Exception("指定分类别名[{$cat}]不存在");
            }
            return parent::getNextLevel($id, $fields->getFields(), $order);
        }else{
            throw new ErrorException('无法识别的节点格式: ' . serialize($cat));
        }
    }
    
    /**
     * 获取一个或多个分类。
     * @param int|string $cat
     *  - 若为数字，视为分类ID获取分类（返回一维数组）；
     *  - 若为字符串，视为分类别名获取分类（返回一维数组）；
     * @param string|array $fields
     * @param null|int|string|array $root 若指定root，则只搜索root下的分类
     *  - 若为数字，视为分类ID
     *  - 若为字符串，视为分类别名
     *  - 若为数组，则必须包含left_value和right_value
     * @return array|bool
     */
    public function get($cat, $fields = '*', $root = null){
        $fields = new FieldsHelper($fields, 'category', CategoriesTable::model()->getFields());
        
        if($root !== null && !is_array($root)){
            if(StringHelper::isInt($root)){
                $root = $this->getById($root, 'left_value,right_value');
            }else{
                $root = $this->getByAlias($root, 'left_value,right_value');
            }
        }
        
        if(StringHelper::isInt($cat)){
            return $this->getById($cat, $fields->getFields(), $root);
        }else{
            return $this->getByAlias($cat, $fields->getFields(), $root);
        }
    }

    /**
     * 通过get()方法获取分类，若获取不到，抛出异常
     * @param int|string $cat
     * @param string|array $fields
     * @param null|int|string|array $root
     * @return array
     * @throws Exception
     */
    public function getOrFail($cat, $fields = '*', $root = null){
        $result = $this->get($cat, $fields, $root);
        if(!$result){
            throw new Exception("指定分类[{$cat}]不存在");
        }
        
        return $result;
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
            $cat1 = $this->getOrFail($cat1, 'left_value,right_value');
        }
        if(!is_array($cat2)){
            $cat2 = $this->getOrFail($cat2, 'left_value,right_value');
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
     * @param string|array $fields
     * @param int|string|array $root
     * @param bool $with_own 是否包含当前节点返回
     * @return array
     */
    public function getParentPath($cat, $fields = '*', $root = null, $with_own = true){
        if(StringHelper::isInt($cat) || is_string($cat)){
            //是数字或字符串，通过当前类的get方法获取左右值信息
            $cat = $this->getOrFail($cat, 'left_value,right_value');
        }

        if(StringHelper::isInt($root) || is_string($root)){
            //是数字或字符串，通过当前类的get方法获取左右值信息
            $root = $this->getOrFail($root, 'left_value,right_value');
        }
        
        return parent::getParentPath(
            $cat,
            '!seo_title,seo_keywords,seo_description,is_system',
            $root,
            $with_own
        );
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
        if(StringHelper::isInt($cat) || is_string($cat)){
            //是数字或字符串，通过当前类的get方法获取左右值信息
            $cat = $this->getOrFail($cat, 'left_value,right_value');
        }
        
        return parent::isTerminal($cat);
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
        $fields = new FieldsHelper($fields, 'category', CategoriesTable::model()->getFields());

        if(StringHelper::isInt($cat) || is_string($cat)){
            //是数字或字符串，通过当前类的get方法获取左右值信息
            $cat = $this->getOrFail($cat, 'left_value,right_value');
        }

        return parent::getSibling($cat, $fields->getFields(), $order);
    }
}