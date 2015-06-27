<?php
namespace fay\core;

use fay\helpers\String;

class Sql{
	protected $fields = array();
	
	protected $from = array();
	
	protected $join = array();
	
	protected $conditions = array();
	
	protected $params = array();
	
	protected $group = array();

	protected $having = array();
	
	protected $order = array();
	
	protected $count = null;
	
	protected $offset = null;
	
	protected $distinct = false;
	
	protected $countBy = '*';
	
	public $operators;
	
	/**
	 * @var Db
	 */
	public $db;
	
	
	public function __construct($db = null){
		$db === null && $db = Db::getInstance();
		
		$this->db = $db;
		$this->operators = $this->db->operators;
	}
	
	public function select($fields){
		$this->_field($fields);
		return $this;
	}
	
	public function distinct($flag = true){
		$this->distinct = $flag;
		return $this;
	}
	
	public function from($table, $alias = null, $fields = '*'){
		$table_name = $this->db->{$table};
		if($alias === null){
			$this->from[] = $table_name;
			$this->_field($fields, $table_name, $table);
		}else{
			$this->from[] = "{$table_name} AS {$alias}";
			$this->_field($fields, $alias, $table);
		}
		return $this;
	}
	
	public function join($table, $alias, $conditions, $fields = null){
		$this->joinInner($table, $alias, $conditions, $fields);
		return $this;
	}
	
	public function joinInner($table, $alias, $conditions, $fields = null){
		$this->_join('INNER JOIN', $table, $alias, $conditions, $fields);
		return $this;
	}
	
	public function joinLeft($table, $alias, $conditions, $fields = null){
		$this->_join('LEFT JOIN', $table, $alias, $conditions, $fields);
		return $this;
	}
	
	public function joinRight($table, $alias, $conditions, $fields = null){
		$this->_join('RIGHT JOIN', $table, $alias, $conditions, $fields);
		return $this;
	}
	
	/**
	 * 默认情况下，以and方式连接各条件
	 * 也可以指定，具体方法见Db::getWhere
	 * @param array $where
	 * @return Sql
	 */
	public function where($where){
		if(is_array($where)){
			foreach($where as $k => $w){
				if(in_array(strtoupper(trim($k)), $this->operators)){
					//若key是关键词，即or，and这些
					$this->conditions = array_merge($this->conditions, array($this->getConditionKey($k, $this->conditions) => $w));
				}else{
					$this->conditions = array_merge($this->conditions, array($k => $w));
				}
			}
		}else{
			$this->conditions[] = $where;
		}
		return $this;
	}
	
	/**
	 * 传入$conditions中各项以or的方式连接
	 * @param array $conditions
	 * @return Sql
	 */
	public function orWhere($conditions){
		$this->where(array(
			'or'=>$conditions,
		));
		return $this;
	}
	
	public function group($group){
		if(!is_array($group)){
			$group = array($group);
		}
		foreach($group as $g){
			$this->group[] = $g;
		}
		return $this;
	}
	
	public function having($having){
		if(is_array($having)){
			foreach($having as $k => $w){
				if(in_array(strtoupper(trim($k)), $this->operators)){
					//若key是关键词，即or，and这些
					$this->having = array_merge($this->having, array($this->getConditionKey($k, $this->having) => $w));
				}else{
					$this->having = array_merge($this->having, array($k => $w));
				}
			}
		}else{
			$this->having[] = $having;
		}
		return $this;
	}
	
	public function order($order){
		if(!is_array($order)){
			$order = array($order);
		}
		foreach($order as $o){
			$this->order[] = $o;
		}
		return $this;
	}
	
	public function limit($count, $offset = null){
		$this->count = $count;
		if($offset !== null && $offset !== false){
			$this->offset = $offset;
		}
		return $this;
	}
	
	/**
	 * 指定count方法根据哪个字段进行计算<br>
	 * 默认为COUNT(*)
	 * @param string $by
	 */
	public function countBy($by){
		$this->countBy = $by;
		return $this;
	}
	
	/**
	 * 得到sql语句
	 * 若传入$count参数，则无视前面设置的offset和count，主要用于fetchRow等特殊情况
	 */
	public function getSql($count = null){
		//清空params，以免多次调用本函数造成params重复
		$this->params = array();
		
		$sql = "SELECT \n";
		//distinct
		if($this->distinct){
			$sql .= "DISTINCT ";
		}
		
		//select
		if(empty($this->fields)){
			$sql .= "* \n";
		}else{
			$sql .= implode(",\n", array_unique($this->fields)). "\n";
		}
		//from
		if($this->from){
			$sql .= "FROM \n".implode(', ', $this->from)."\n";
		}
		//join
		if(!empty($this->join)){
			foreach($this->join as $j){
				$sql .= "{$j['type']} {$j['table']} ";
				if(!empty($j['alias'])){
					$sql .= "AS {$j['alias']} ";
				}
				$sql .= "ON ({$j['condition']}) \n";
				$this->params = array_merge($this->params, $j['params']);
			}
		}
		//where
		if(!empty($this->conditions)){
			$where = $this->db->getWhere($this->conditions);
			$sql .= "WHERE \n{$where['condition']} \n";
			$this->params = array_merge($this->params, $where['params']);
		}
		//group
		if(!empty($this->group)){
			$sql .= "GROUP BY \n".implode(", \n", $this->group)." \n";
		}
		//having
		if(!empty($this->having)){
			$having = $this->db->getWhere($this->having);
			$sql .= "HAVING \n{$having['condition']} \n";
			$this->params = array_merge($this->params, $having['params']);
		}
		
		//order
		if(!empty($this->order)){
			$sql .= "ORDER BY \n".implode(", \n", $this->order)." \n";
		}
		//limit
		if($count !== null){
			$sql .= "LIMIT {$count} \n";
		}else{
			if(!empty($this->count)){
				if($this->offset !== null && $this->offset !== false){
					$sql .= "LIMIT {$this->offset} {$this->count} \n";
				}else{
					$sql .= "LIMIT {$this->count} \n";
				}
			}
		}
		return $sql;
	}
	
	/**
	 * 得到count用的sql语句
	 * 若设置了distinct参数，则无视前面设置的distinct参数
	 */
	public function getCountSql(){
		//清空params，以免多次调用本函数造成params重复
		$this->params = array();
		
		$sql = "SELECT COUNT({$this->countBy}) \n";
		
		//from
		$sql .= "FROM \n".implode(', ', $this->from)."\n";
		//join
		if(!empty($this->join)){
			foreach($this->join as $j){
				$sql .= "{$j['type']} {$j['table']} ";
				if(!empty($j['alias'])){
					$sql .= "AS {$j['alias']} ";
				}
				$sql .= "ON ({$j['condition']}) \n";
				$this->params = array_merge($this->params, $j['params']);
			}
		}
		//where
		if(!empty($this->conditions)){
			$where = $this->db->getWhere($this->conditions);
			$sql .= "WHERE {$where['condition']} \n";
			$this->params = array_merge($this->params, $where['params']);
		}
		return $sql;
	}
	
	public function getParams(){
		return $this->params;
	}
	
	public function fetchAll($reset = true, $style = 'assoc'){
		$result = $this->db->fetchAll($this->getSql(), $this->getParams(), $style);
		if($reset){
			$this->reset();
		}
		return $result;
	}
	
	public function fetchRow($reset = true, $style = 'assoc'){
		$result = $this->db->fetchRow($this->getSql(1), $this->getParams(), $style);
		if($reset){
			$this->reset();
		}
		return $result;
	}
	
	public function count(){
		$result = $this->db->fetchRow($this->getCountSql(), $this->getParams());
		return array_shift($result);
	}
	
	/**
	 * 重置搜索条件
	 */
	public function reset(){
		$this->fields = array();
		$this->from = array();
		$this->join = array();
		$this->conditions = array();
		$this->params = array();
		$this->group = array();
		$this->having = array();
		$this->order = array();
		$this->count = null;
		$this->offset = null;
		$this->countBy = '*';
		$this->distinct = false;
	}
	
	/**
	 * 构造fields数组
	 * @param string $fields 若传入的fields为反选类型，则必须传入表名（用于获取表结构）
	 * @param string $alias 此处alias为表的别名
	 * @param string $table 表名
	 */
	private function _field($fields, $alias = null, $table = null){
		if(is_string($fields) && strpos($fields, '!') === 0){
			if(strpos($table, APPLICATION.'_') === 0){
				$all_fields = \F::model(APPLICATION.'\models\tables\\'.String::underscore2case($table))->getFields();
			}else{
				$all_fields = \F::model('fay\models\tables\\'.String::underscore2case($table))->getFields();
			}
			$fields = '`'.implode('`,`', array_diff($all_fields, explode(',', str_replace(' ', '', ltrim($fields, '! '))))).'`';
		}
		if(!empty($fields)){
			if(!is_array($fields)){
				$fields = array($fields);
			}
			foreach($fields as $f){
				$f_arr = explode(',', $f);
				if(!empty($alias)){
					foreach($f_arr as &$fa){
						if(!preg_match('/^\w+\(.*\).*$/', $fa)){//聚合函数不加前缀
							$fa = trim($fa);
							if(strpos($fa, '`') !== 0 && $fa != '*'){//本身没加引号，且非通配符
								if($pos = strpos($fa, ' ')){//存在空格，例如设置了AS
									$fa = $alias . '.`' . substr($fa, 0, $pos) . '`' . substr($fa, $pos);
								}else{
									$fa = "{$alias}.`{$fa}`";
								}
							}else{
								$fa = "{$alias}.{$fa}";
							}
						}
					}
				}
				$this->fields = array_merge($this->fields, $f_arr);
			}
		}
	}
	
	private function _join($type, $table, $alias, $conditions, $fields){
		$table_name = $this->db->{$table};
		$where = $this->db->getWhere($conditions);
		$this->join[] = array(
			'type'=>$type,
			'table'=>$table_name,
			'alias'=>$alias,
			'condition'=>$where['condition'],
			'params'=>$where['params'],
		);
		if(!empty($fields)){
			$this->_field($fields, $alias ? $alias : $table_name, $table);
		}
	}
	
	/**
	 * 通过不停加后缀空格的方式，使关键词的键名不重名
	 * @param string $key
	 */
	private function getConditionKey($key, $conditions){
		if(isset($conditions[$key])){
			return $this->getConditionKey($key . ' ', $conditions);
		}else{
			return $key;
		}
	}
}