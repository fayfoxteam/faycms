<?php
namespace fay\core\db;

use fay\core\Db;
use fay\core\Model;
use fay\core\Sql;
use fay\helpers\ArrayHelper;
use fay\helpers\StringHelper;

class Table extends Model{
    protected $_name = '';
    protected $_primary = 'id';//主键
    
    /**
     * @var \fay\core\Db
     */
    private $db = null;
    
    public function __construct(){
        $this->db = Db::getInstance();
    }
    
    /**
     * 获取数据库链接实例
     * @return Db
     */
    public function getDb(){
        return $this->db;
    }
    
    /**
     * 设置数据库链接实例
     * @param Db $db
     */
    public function setDb($db){
        $this->db = $db;
    }
    
    /**
     * 获取表名（不带前缀）
     */
    public function getTableName(){
        return $this->_name;
    }
    
    /**
     * 获取完整表名（带前缀）
     */
    public function getFullTableName(){
        return $this->db->{$this->_name};
    }
    
    /**
     * 根据表字段填充数据，仅$this->getFields()中存在的字段会被返回
     * @param array $data
     * @param bool $filter 若为true，则会根据$this->filters()中指定的过滤器进行过滤。默认为true
     * @param array|string $except 填充数据时，排序某些字段
     * @return array
     */
    public function fillData($data, $filter = true, $except = array()){
        $filters = $this->filters();
        $fields = $this->getFields($except);
        $return = array();
        foreach($data as $k => $v){
            if(in_array($k, $fields)){
                $return[$k] = $filter && isset($filter[$k]) ? \F::filter($filters[$k], $v) : $v;
            }
        }
        return $return;
    }
    
    /**
     * 向当前表插入单行数据
     * @param array $data 一维数组
     * @param bool $fill 是否进行字段过滤
     * @param string $except
     * @return int|null
     */
    public function insert($data, $fill = false, $except = 'insert'){
        if(!empty($data)){
            if($fill){
                $data = $this->fillData($data, false, $except);
            }
            return $this->db->insert($this->_name, $data);
        }else{
            return null;
        }
    }
    
    /**
     * 向当前表批量插入
     * @param array $data 二维数组
     * @param bool $filter 是否调用过滤器进行过滤
     * @return int|null
     */
    public function bulkInsert($data, $filter = false){
        if(!empty($data)){
            $insert_data = array();
            foreach($data as $d){
                $insert_data[] = $filter ? $this->fillData($d) : $d;
            }
            
            return $this->db->bulkInsert($this->_name, $insert_data);
        }else{
            return null;
        }
    }
    
    /**
     * 更新当前表记录
     * @param array $data
     * @param mixed $where 条件。若传入一个数字，视为根据主键进行删除（仅适用于单主键的情况）
     * @param bool $fill 若为true，则会与$except字段配合进行字段过滤，排除不存在的字段和except中指定的字段
     * @param string $except 当$fill为true时，指定不更新某些字段
     * @return int|null
     * @throws \fay\core\Exception
     */
    public function update($data, $where, $fill = false, $except = 'update'){
        if(StringHelper::isInt($where)){
            $where = array("{$this->_primary} = ?" => $where);
        }
        if(!empty($data)){
            if($fill){
                $data = $this->fillData($data, false, $except);
            }
            return $this->db->update($this->_name, $data, $where);
        }else{
            return null;
        }
    }
    
    /**
     * 删除一条记录
     * @param mixed $where 条件。若传入一个数字，视为根据主键进行删除（仅适用于单主键的情况）
     * @return int
     */
    public function delete($where){
        if(StringHelper::isInt($where)){
            $where = array("{$this->_primary} = ?" => $where);
        }
        return $this->db->delete($this->_name, $where);
    }
    
    /**
     * 递增指定列
     * @param mixed $where
     * @param string|array $fields 列名
     * @param int $value 增量（可以是负数）
     * @return int
     */
    public function incr($where, $fields, $value){
        if(StringHelper::isInt($where)){
            $where = array("{$this->_primary} = ?" => $where);
        }
        return $this->db->incr($this->_name, $where, $fields, $value);
    }
    
    /**
     * 根据主键查找数据
     * @param mixed $primary
     * @param string $fields
     * @return array|bool
     */
    public function find($primary, $fields = '*'){
        $sql = new Sql($this->db);
        $sql->from($this->_name, $this->formatFields($fields))
            ->limit(1);
        if(is_array($this->_primary)){
            foreach($this->_primary as $k=>$pk){
                $sql->where(array("$pk = ?"=>isset($primary[$pk]) ? $primary[$pk] : $primary[$k]));
            }
        }else{
            $sql->where(array(
                "{$this->_primary} = ?" => $primary,
            ));
        }
        return $sql->fetchRow();
    }
    
    /**
     * 获取一条记录
     * @param array|string $conditions
     * @param string $fields 可用 !id 表示除了id外的所有字段
     * @param bool|string $order
     * @param null $offset
     * @param string $style 返回结果集类型，默认为索引数组
     * @return array|bool
     */
    public function fetchRow($conditions, $fields = '*', $order = false, $offset = null, $style = 'assoc'){
        $sql = new Sql($this->db);
        $sql->from($this->_name, $this->formatFields($fields))
            ->where($conditions)
            ->limit(1, $offset);
        if($order){
            $sql->order($order);
        }
        return $sql->fetchRow(true, $style);
    }
    
    /**
     * 获取所有数据
     * @param array $conditions
     * @param string $fields 可用 !id 表示除了id外的所有字段
     * @param bool|string $order
     * @param bool|int $count
     * @param bool|int $offset
     * @param string $style 返回结果集类型，默认为索引数组
     * @return array
     */
    public function fetchAll($conditions = array(), $fields = '*', $order = false, $count = false, $offset = false, $style = 'assoc'){
        $sql = new Sql($this->db);
        $sql->from($this->_name, $this->formatFields($fields))
            ->where($conditions);
        if($order){
            $sql->order($order);
        }
        if($count){
            $sql->limit($count, $offset);
        }
        return $sql->fetchAll(true, $style);
    }
    
    /**
     * 以一维数组的方式，返回一列结果
     * @param string $col
     * @param array $conditions
     * @param bool $order
     * @param bool $count
     * @param bool $offset
     * @return array
     */
    public function fetchCol($col, $conditions = array(), $order = false, $count = false, $offset = false){
        $result = $this->fetchAll($conditions, $col, $order, $count, $offset);
        
        return ArrayHelper::column($result, $col);
    }
    
    /**
     * 获取表所有字段
     * @param array|string $except 返回字段不包含except中指定的字段
     *  - 若为数组，则数组项为不返回字段
     *  - 若为字符串，则视为getNotWritableFields()函数的$scene值
     * @return array:
     */
    public function getFields($except = array()){
        if($except){
            if(is_string($except)){
                $except = $this->getNotWritableFields($except);
            }
            $labels = $this->labels();
            foreach($except as $e){
                unset($labels[$e]);
            }
            return array_keys($labels);
        }else{
            return array_keys($this->labels());
        }
    }
    
    /**
     * 获取只读字段。
     * insert(), update()方法当$fill参数为true时，会自动调用此方法用于过滤字段。
     * 也可以手动调用此方法用于字段过滤处理。
     * @param mixed $scene 场景
     * @return array
     */
    public function getNotWritableFields($scene){
        switch($scene){
            case 'insert':
            case 'update':
            default:
                return array();
        }
    }
    
    /**
     * 格式化传入字段
     * @param string|array $fields 若是字符串，先逗号分割为数组。当有一项是*，则返回全部字段。
     * @return array 表字段
     */
    public function formatFields($fields){
        if(!is_array($fields)){
            if(is_string($fields) && strpos($fields, '!') === 0){
                //若不是数组，且首字母是感叹号，则视为排除指定字段
                $except_fields = explode(',', str_replace(' ', '', substr($fields, 1)));
                return $this->getFields($except_fields);
            }else{
                //最常规的逗号分割，拆成数组后面处理
                $fields = explode(',', $fields);
            }
        }
        
        if(in_array('*', $fields)){
            //当有一项是*，则返回全部字段
            return $this->getFields();
        }else{
            return $fields;
        }
    }
}