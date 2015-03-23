<?php
namespace fay\core\db;

use fay\core\Model;
use fay\core\Sql;
use fay\helpers\ArrayHelper;

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
	 * 根据filters返回的参数列表，设置table相关参数
	 * 并进行相关的filter处理
	 * 没有设置filter的数据将不会被返回
	 * @param array $options
	 */
	public function setAttributes($data){
		$filters = $this->filters();
		$return = array();
		foreach($data as $k => $v){
			if(isset($filters[$k])){
				$return[$k] = \F::filter($filters[$k], $v);
			}
		}
		return $return;
	}
	
	/**
	 * 向当前表插入
	 * @param array $options
	 */
	public function insert($options, $filter = false){
		if(!empty($options)){
			if($filter){
				$options = $this->setAttributes($options);
			}
			return $this->db->insert($this->_name, $options);
		}else{
			return null;
		}
	}
	
	/**
	 * 更新当前表记录
	 * @param array $options
	 * @param array $where
	 * @param boolean $filter
	 */
	public function update($options, $where, $filter = false){
		if(is_numeric($where)){
			$where = array("{$this->_primary} = ?" => $where);
		}
		if(!empty($options)){
			if($filter){
				$options = $this->setAttributes($options);
			}
			return $this->db->update($this->_name, $options, $where);
		}else{
			return null;
		}
	}
	
	/**
	 * 删除一条记录
	 * 单主键自递增的情况下可以进传入一个数字，视为根据主键进行删除
	 * @param mix $where
	 */
	public function delete($where){
		if(is_numeric($where)){
			$where = array("{$this->_primary} = ?" => $where);
		}
		return $this->db->delete($this->_name, $where);
	}
	
	public function inc($where, $field, $count){
		if(is_numeric($where)){
			$where = array("{$this->_primary} = ?" => $where);
		}
		$this->db->inc($this->_name, $where, $field, $count);
	}
	
	/**
	 * 根据主键查找数据
	 */
	public function find($primary, $fields = '*'){
		if(!$this->_sql)$this->_sql = new Sql();
		$this->_sql->from($this->_name, $this->_name, $fields)
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
		$this->_sql->from($this->_name, $this->_name, $fields)
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
		$this->_sql->from($this->_name, $this->_name, $fields)
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
	 * @return array:
	 */
	public function getFields(){
		return array_keys($this->labels());
	}
}