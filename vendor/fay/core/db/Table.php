<?php
namespace fay\core\db;

use fay\core\Model;
use fay\core\Sql;
use fay\helpers\ArrayHelper;
use fay\helpers\StringHelper;

class Table extends Model{
	protected $_name = '';
	protected $_primary = 'id';//主键
	/**
	 * @var Sql
	 */
	protected $_sql;
	
	public function __construct(){
		parent::__construct();
	}
	
	public function getName(){
		return $this->_name;
	}

	public function tableName(){
		return $this->db->{$this->_name};
	}
	
	/**
	 * 根据表字段填充数据，仅$this->getFields()中存在的字段会被返回
	 * @param array $data
	 * @param bool $filter 若为true，则会根据$this->filters()中指定的过滤器进行过滤。默认为true
	 * @param array|string $except 填充数据时，排序某些字段
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
	 * @param bool $filter 是否调用过滤器进行过滤
	 * @param array|string 执行fillData时，过滤掉部分字段
	 */
	public function insert($data, $fill = false, $except = array()){
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
	 * @param boolean $filter 若为true且$this->filters()中有设置过滤器，则进行过滤
	 * @param array|string 执行fillData时，过滤掉部分字段
	 */
	public function update($data, $where, $fill = false, $except = array()){
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
	 * @param mix $where 条件。若传入一个数字，视为根据主键进行删除（仅适用于单主键的情况）
	 */
	public function delete($where){
		if(StringHelper::isInt($where)){
			$where = array("{$this->_primary} = ?" => $where);
		}
		return $this->db->delete($this->_name, $where);
	}
	
	/**
	 * 递增指定列
	 * @param mix $where
	 * @param string $fields 列名
	 * @param int $value 增量（可以是负数）
	 */
	public function incr($where, $fields, $value){
		if(StringHelper::isInt($where)){
			$where = array("{$this->_primary} = ?" => $where);
		}
		$this->db->incr($this->_name, $where, $fields, $value);
	}
	
	/**
	 * 根据主键查找数据
	 */
	public function find($primary, $fields = '*'){
		if(!$this->_sql)$this->_sql = new Sql();
		$this->_sql->from($this->_name, $this->formatFields($fields))
			->limit(1);
		if(is_array($this->_primary)){
			foreach($this->_primary as $k=>$pk){
				$this->_sql->where(array("$pk = ?"=>isset($primary[$pk]) ? $primary[$pk] : $primary[$k]));
			}
		}else{
			$this->_sql->where(array(
				"{$this->_primary} = ?" => $primary,
			));
		}
		return $this->_sql->fetchRow();
	}
	
	/**
	 * 获取一条记录
	 * @param array $conditions
	 * @param string $fields 可用 !id 表示除了id外的所有字段
	 * @param string $order
	 * @param string $style 返回结果集类型，默认为索引数组
	 */
	public function fetchRow($conditions, $fields = '*', $order = false, $style = 'assoc'){
		if(!$this->_sql)$this->_sql = new Sql();
		$this->_sql->from($this->_name, $this->formatFields($fields))
			->where($conditions)
			->limit(1);
		if($order){
			$this->_sql->order($order);
		}
		return $this->_sql->fetchRow(true, $style);
	}
	
	/**
	 * 获取所有数据
	 * @param array $conditions
	 * @param string $fields 可用 !id 表示除了id外的所有字段
	 * @param string $order
	 * @param int $count
	 * @param int $offset
	 * @param string $style 返回结果集类型，默认为索引数组
	 */
	public function fetchAll($conditions = array(), $fields = '*', $order = false, $count = false, $offset = false, $style = 'assoc'){
		if(!$this->_sql)$this->_sql = new Sql();
		$this->_sql->from($this->_name, $this->formatFields($fields))
			->where($conditions);
		if($order){
			$this->_sql->order($order);
		}
		if($count){
			$this->_sql->limit($count, $offset);
		}
		return $this->_sql->fetchAll(true, $style);
	}
	
	/**
	 * 以一维数组的方式，返回一列结果
	 * @param string $col
	 * @param string $sql
	 * @param array $params
	 */
	public function fetchCol($col, $conditions = array(), $order = false, $count = false, $offset = false){
		$result = $this->fetchAll($conditions, $col, $order, $count, $offset);
		
		return ArrayHelper::column($result, $col);
	}
	
	/**
	 * 获取表所有字段
	 * @param array|string $except 返回字段不包含except中指定的字段
	 * @return array:
	 */
	public function getFields($except = array()){
		if($except){
			if(!is_array($except)){
				$except = explode(',', $except);
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