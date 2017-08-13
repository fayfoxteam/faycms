<?php
namespace cms\services;

use cms\models\tables\CategoriesTable;
use fay\core\Loader;
use fay\helpers\FieldsHelper;
use fay\helpers\StringHelper;
use fay\models\TreeModel;

class CategoryService extends TreeModel{
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
    
    protected function getModel(){
        return CategoriesTable::model();
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

        if($root && (!isset($root['left_value']) || !isset($root['right_value']))){
            //root信息不足，尝试通过get()方法获取
            $root = $this->getOrFail($root, 'left_value,right_value');
        }

        $conditions = array();
        if(StringHelper::isInt($cat)){
            $conditions['id = ?'] = $cat;
        }else if(is_string($cat)){
            $conditions['alias = ?'] = $cat;
        }else{
            throw new \InvalidArgumentException('无法识别的节点格式: ' . serialize($cat));
        }

        if($root){
            $conditions['left_value >= ?'] = $root['left_value'];
            $conditions['right_value <= ?'] = $root['right_value'];
        }
        return CategoriesTable::model()->fetchRow($conditions, $fields->getFields());
    }

    /**
     * 根据分类ID串获取多个分类信息
     * @param string|array $ids 多个分类ID（数组或者逗号分隔），返回数组会与传入id顺序一致并以id为数组键
     * @param string $fields 可选categories表字段
     * @param int|string|array $root 若指定root，则只搜索root下的分类
     *  - 若为数字，视为分类ID
     *  - 若为字符串，视为分类别名
     *  - 若为数组，则必须包含left_value和right_value
     * @return array
     */
    public function mget($ids, $fields = '*', $root = null){
        if(is_string($ids)){
            $ids = explode(',', $ids);
        }
        
        if(!is_array($ids)){
            throw new \InvalidArgumentException('ids参数格式异常: ' . serialize($ids));
        }
        
        $fields = new FieldsHelper($fields, 'category');
        
        $table_fields = CategoriesTable::model()->formatFields($fields->getFields());
        $remove_id = false;//最受是否删除id字段
        if(!in_array('id', $table_fields)){
            //id必须搜出，若为指定，则先插入id字段，到后面再unset掉
            $table_fields[] = 'id';
            $remove_id = true;
        }
        if($root && (!isset($root['left_value']) || !isset($root['right_value']))){
            //root信息不足，尝试通过get()方法获取
            $root = $this->getOrFail($root, 'left_value,right_value');
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
     * 根据父节点，获取其下一级节点
     * @param int $cat
     *  - 若为数字，视为分类ID获取分类
     *  - 若为字符串，视为分类别名获取分类
     * @param string $fields 返回字段
     * @param string $order 排序规则
     * @return array
     */
    public function getNextLevel($cat, $fields = '!seo_title,seo_keywords,seo_description,is_system', $order = 'sort, id'){
        $fields = new FieldsHelper($fields, 'category', CategoriesTable::model()->getFields());
        
        if(StringHelper::isInt($cat)){
            return parent::getNextLevel($cat, $fields->getFields(), $order);
        }else if(is_string($cat)){
            //子类中重写此方法是为了用getIdByAlias这个方法，因为这个方法很容易做缓存
            $id = $this->getIdByAlias($cat);
            if(!$id){
                throw new \UnexpectedValueException("指定分类别名[{$cat}]不存在");
            }
            return parent::getNextLevel($id, $fields->getFields(), $order);
        }else{
            throw new \InvalidArgumentException('无法识别的节点格式: ' . serialize($cat));
        }
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
     * 判断指定id是否存在，可限制根节点
     * @param int $cat_id
     * @param null|int|string|array $root 若指定root，则只搜索root下的分类
     * @return bool
     */
    public function isIdExist($cat_id, $root = null){
        return !!$this->get($cat_id, 'id', $root);
    }
}