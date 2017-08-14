<?php
namespace fay\core;

use fay\core\db\DBException;
use fay\core\db\Expr;
use fay\core\db\QueryException;
use fay\helpers\SqlHelper;

class Db{
    private $_host;
    private $_user;
    private $_pwd;
    private $_port = 3306;
    private $_dbname;
    private $_charset;
    /**
     * @var \PDO
     */
    private $_conn;
    private $_table_prefix;
    private $_debug = false;
    private static $_instance;
    public $matches = array('=', '<>', '<', '<=', '>', '>=', 'like', 'not like', 'is null',
        'is not null', 'is between', 'is not between', 'is in list', 'is not in list');
    public $operators = array('AND', 'OR', 'AND NOT', 'OR NOT');
    
    /**
     * sql执行总次数
     * @var int
     */
    private static $_count = 0;
    
    private static $_sqls = array();
    
    private function __construct(){}
    
    private function __clone(){}
    
    public static function getInstance($config = array()){
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self();
            self::$_instance->init($config);
        }
        return self::$_instance;
    }
    
    /**
     * 判断是否有实例化过，即判断当前请求是否连接过数据库
     * @return bool
     */
    public static function hasInstance(){
        return !empty(self::$_instance);
    }
    
    /**
     * 初始化
     * @param array $config
     * @throws DBException
     */
    public function init($config){
        $db_config = \F::config()->get('db');
        $this->_host = isset($config['host']) ? $config['host'] : $db_config['host'];
        $this->_user = isset($config['user']) ? $config['user'] : $db_config['user'];
        $this->_pwd = isset($config['password']) ? $config['password'] : $db_config['password'];
        $this->_port = !empty($config['port']) ? $config['port'] : (!empty($db_config['port']) ? $db_config['port'] : 3306);
        $this->_dbname = isset($config['dbname']) ? $config['dbname'] : $db_config['dbname'];
        $this->_charset = isset($config['charset']) ? $config['charset'] : $db_config['charset'];
        $this->_table_prefix = isset($config['table_prefix']) ? $config['table_prefix'] : $db_config['table_prefix'];
        $this->_debug = isset($config['debug']) ? $config['debug'] : \F::config()->get('debug');
        $this->getConn();
    }
    
    public function getConn(){
        if(!$this->_conn){
            $dsn = "mysql:host={$this->_host};port={$this->_port};dbname={$this->_dbname};charset={$this->_charset}";
            try {
                $this->_conn = new \PDO($dsn, $this->_user, $this->_pwd);
                //当发生错误时，以异常的形式抛出（默认只是返回false）
                $this->_conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            }catch(\PDOException $e){
                throw new DBException($e->getMessage(), '数据库链接失败，请确认configs/main.php中数据库配置正确');
            }
            $this->_conn->exec("SET NAMES {$this->_charset}");
        }
        return $this->_conn;
    }
    
    /**
     * 执行一条sql语句，若是insert语句，则返回插入后产生的自递增id号，
     * 若是update或delete语句，则返回受影响的记录条数
     * @param string $sql
     * @param array $params
     * @return int
     */
    public function execute($sql, $params = array()){
        $start_time = microtime(true);
        try{
            $sth = $this->_conn->prepare($sql);
            $sth->execute($params);
        }catch(\PDOException $e){
            throw new QueryException($sql, $params, $e);
        }
        
        self::$_count++;
        $this->logSql($sql, $params, microtime(true) - $start_time);
        if(strtolower(substr(trim($sql), 0, 6)) == 'insert'){
            return $this->_conn->lastInsertId();
        }else{
            return $sth->rowCount();
        }
    }
    
    /**
     * 执行一条或多条SQL
     * 全部成功返回true，失败会抛出异常。
     * @param string $sql
     * @param bool $explode 默认为false。若为true，则会把"\r\n"替换为"\n"后根据";\n"分割为多个SQL依次执行
     * （这并不是很完美的解决方案，因为从语法上讲，SQL并不一定要一行一个，而且极端情况下可能出错。不过适用于数据导入等情况）
     */
    public function exec($sql, $explode = false){
        if($explode){
            $sqls = explode(";\n", str_replace("\r\n", "\n", $sql));
            foreach($sqls as $s){
                $s = trim($s);
                if(!$s){
                    continue;
                }
                $start_time = microtime(true);
                try{
                    $this->_conn->exec($s);
                }catch(\PDOException $e){
                    throw new QueryException($sql, array(), $e);
                }
                self::$_count++;
                $this->logSql($s, array(), microtime(true) - $start_time);
            }
        }else{
            try{
                $this->_conn->exec($sql);
            }catch(\PDOException $e){
                throw new QueryException($sql, array(), $e);
            }
        }
    }
    
    /**
     * 返回所有查询数据，如果没有符合条件的数据，则返回空数组
     * @param string $sql
     * @param array $params
     * @param string $style
     * @return array
     */
    public function fetchAll($sql, $params = array(), $style = 'assoc'){
        if($style == 'num'){
            $result_style = \PDO::FETCH_NUM;
        }else if($style == 'both'){
            $result_style = \PDO::FETCH_BOTH;
        }else{
            $result_style = \PDO::FETCH_ASSOC;
        }
        
        $start_time = microtime(true);
        try{
            $sth = $this->_conn->prepare($sql);
            $sth->execute($params);
        }catch(\PDOException $e){
            throw new QueryException($sql, $params, $e);
        }

        $result = $sth->fetchAll($result_style);
        $this->logSql($sql, $params, microtime(true) - $start_time);
        self::$_count++;
        return $result;
    }
    
    /**
     * 返回第一条查询数据，如果没有符合条件的数据，则返回false
     * @param string $sql
     * @param array $params
     * @param string $style
     * @return array|bool
     */
    public function fetchRow($sql, $params = array(), $style = 'assoc'){
        if($style == 'num'){
            $result_style = \PDO::FETCH_NUM;
        }else if($style == 'both'){
            $result_style = \PDO::FETCH_BOTH;
        }else{
            $result_style = \PDO::FETCH_ASSOC;
        }
        
        $start_time = microtime(true);
        try{
            $sth = $this->_conn->prepare($sql);
            $sth->execute($params);
        }catch(\PDOException $e){
            throw new QueryException($sql, $params, $e);
        }
        
        $result = $sth->fetch($result_style);
        $this->logSql($sql, $params, microtime(true) - $start_time);
        self::$_count++;
        return $result;
    }
    
    /**
     * 单条插入
     * @param string $table 表名
     * @param array $data 数据
     * @return int
     */
    public function insert($table, $data){
        $fields = array();
        $pres = array();
        $values = array();
        foreach($data as $k => $v){
            if($v === false)continue;
            if($v instanceof Expr){
                $fields[] = "`{$k}`";
                $pres[] = $v->get();
            }else{
                $fields[] = "`{$k}`";
                $pres[] = '?';
                $values[] = $v;
            }
        }
        $sql = "INSERT INTO {$this->getFullTableName($table)} (".implode(',', $fields).') VALUES ('.implode(',', $pres).')';
        return $this->execute($sql, $values);
    }
    
    /**
     * 批量插入（要求二维数组所有数组项结构一致）
     * @param string $table 表名
     * @param array $data 插入数据
     * @return int
     */
    public function bulkInsert($table, $data){
        $fields = array();
        $pres = array();
        $values = array();
        $bulk = array();
        //取第一项构造fields
        $first_item = array_shift($data);
        foreach($first_item as $k => $v){
            if($v === false)continue;
            if($v instanceof Expr){
                $fields[] = "`{$k}`";
                $pres[] = $v->get();
            }else{
                $fields[] = "`{$k}`";
                $pres[] = '?';
                $values[] = $v;
            }
        }
        $bulk[] = implode(',', $pres);
        foreach($data as $item){
            $pres = array();
            foreach($item as $i){
                if($i instanceof Expr){
                    $pres[] = $i->get();
                }else{
                    $pres[] = '?';
                    $values[] = $i;
                }
            }
            $bulk[] = implode(',', $pres);
        }
        $sql = "INSERT INTO {$this->getFullTableName($table)} (".implode(',', $fields).') VALUES ('.implode("),\n(", $bulk).')';
        return $this->execute($sql, $values);
    }
    
    /**
     * 更新符合条件的记录
     * @param string $table 表名
     * @param array $data 数据
     * @param array|bool|false|string $condition 条件，若为false，则更新所有字段
     * @return int
     * @throws DBException
     */
    public function update($table, $data, $condition = false){
        if(empty($data)){
            throw new DBException('更新数据不能为空');
        }
        if(!$condition){
            throw new DBException('出于安全考虑，不允许where条件为空的update操作');
        }
        
        $set = array();
        $values = array();
        foreach($data as $k => $v){
            if($v === false)continue;
            if($v instanceof Expr){
                $set[] = "`{$k}` = {$v->get()}";
            }else{
                $set[] = "`{$k}` = ?";
                $values[] = $v;
            }
        }
        
        $where = $this->formatConditions($condition);
        $sql = "UPDATE {$this->getFullTableName($table)} SET ".implode(',', $set)." WHERE {$where['condition']}";
        return $this->execute($sql, array_merge($values, $where['params']));
    }
    
    /**
     * 根据条件删除行
     * @param string $table 表名
     * @param array|string $condition 条件，出于安全考虑，$condition不能为空，即不可全表删除
     * @return int
     */
    public function delete($table, $condition){
        $where = $this->formatConditions($condition);
        $sql = "DELETE FROM {$this->getFullTableName($table)} WHERE {$where['condition']}";
        return $this->execute($sql, $where['params']);
    }
    
    /**
     * 递增/递减一个字段
     * @param string $table 表名
     * @param array|string $condition where条件
     * @param string|array $fields 字段（可以是多个，多个字段以一维数组方式传入）
     * @param $value
     * @return int
     */
    public function incr($table, $condition, $fields, $value){
        if(is_string($fields)){
            $fields = explode(',', $fields);
        }
        $data = array();
        foreach($fields as $f){
            $data[$f] = new Expr("`{$f}` + {$value}");
        }
        return $this->update($table, $data, $condition);
    }
    
    /**
     * 给传入字段加上表前缀后返回
     * @param string $table_name
     * @return string
     */
    public function __get($table_name){
        return $this->getFullTableName($table_name);
    }
    
    /**
     * 给表名加上表名前缀
     * @param string $table_name
     * @return string
     */
    public function getFullTableName($table_name){
        return $this->_table_prefix . $table_name;
    }
    
    /**
     * 构造一个where语句以及相关参数
     * @param array $where
     * @return array array('condition', 'params')
     */
    public function formatConditions($where){
        if(is_array($where)){
            $condition = '';
            $params = array();
            foreach($where as $key => $value){
                if($value === false)continue;
                if(in_array(strtoupper(trim($key)), $this->operators)){
                    $op = ' ' . strtoupper($key) . ' ';
                    $partial = $this->getPartialCondition($op, $value);
                    if($condition != ''){$condition .= ' AND ';}
                    $condition .= $partial['condition'];
                    $params = array_merge($params, $partial['params']);
                }else{
                    $op = ' AND ';
                    if($condition != ''){$condition .= $op;}
                    if(is_int($key)){//'id = 1'
                        $condition .= $value;
                    }else{//'id = ?'=>1
                        if(!$this->_hasOperator($key)){//'id'=>1
                            //不带操作符的key，默认为等于
                            $key .= ' = ?';
                        }
                        if(is_array($value)){
                            $params = array_merge($params, $value);
                            if(substr_count($key, '?') == 1 && count($value) > 1){
                                $key = str_replace('?', '?'.str_repeat(', ?', count($value) - 1), $key);
                            }
                        }else{
                            $params[] = $value;
                        }
                        $condition .= $key;
                    }
                }
            }
            return array(
                'condition'=>$condition,
                'params'=>$params,
            );
        }else{
            return array(
                'condition'=>$where,
                'params'=>array(),
            );
        }
    }
    
    /**
     * 判断是否有SQL操作符
     * @param string $str
     * @return bool
     */
    protected function _hasOperator($str){
        return (bool) preg_match('/(<|>|!|=|\sIS NULL|\sIS NOT NULL|\sEXISTS|\sBETWEEN|\sLIKE|\sIN\s*\(|\s)/i', trim($str));
    }
    
    private function getPartialCondition($op, $condition_arr){
        $partial_condition = array();
        $params = array();
        foreach($condition_arr as $key => $value){
            if(in_array(strtoupper($key), $this->operators)){
                $partial = $this->getPartialCondition(' ' . strtoupper($key) . ' ', $value);
                $partial_condition[] = $partial['condition'];
                $params = array_merge($params, $partial['params']);
            }else{
                if(is_int($key)){//'id = 1'
                    $partial_condition[] = $value;
                }else{//'id = ?'=>1
                    if(is_array($value)){
                        $params = array_merge($params, $value);
                        if(substr_count($key, '?') == 1 && count($value) > 1){
                            $key = str_replace('?', '?'.str_repeat(', ?', count($value) - 1), $key);
                        }
                    }else{
                        $params[] = $value;
                    }
                    $partial_condition[] = $key;
                }
            }
        }
        $condition = ' ( ' . implode($op, $partial_condition) . ' ) ';
        return array(
            'condition'=>$condition,
            'params'=>$params
        );
    }
    
    /**
     * 抛出一个错误异常
     * @param string $message
     * @param string $sql
     * @param array $params
     * @throws DBException
     */
    public function error($message, $sql = '', $params = array()){
        if(is_array($message)){
            $message = implode(' - ', $message);
        }
        throw new DBException($message, $sql ? SqlHelper::bind($sql, $params) : '');
    }
    
    /**
     * 启动一个事务
     */
    public function beginTransaction(){
        $this->_conn->beginTransaction();
    }
    
    /**
     * 提交一个事务
     */
    public function commit(){
        $this->_conn->commit();
    }
    
    /**
     * 回滚一个事务 
     */
    public function rollBack(){
        $this->_conn->rollBack();
    }
    
    /**
     * 返回sql执行次数
     * @return number
     */
    public function getCount(){
        return self::$_count;
    }
    
    private function logSql($sql, $params = array(), $time){
        if($this->_debug){
            self::$_sqls[] = array($sql, $params, $time);
        }
    }
    
    public function getSqlLogs(){
        return self::$_sqls;
    }
}