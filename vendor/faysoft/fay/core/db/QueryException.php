<?php
namespace fay\core\db;

use fay\helpers\SqlHelper;

class QueryException extends \PDOException{
    /**
     * @var string
     */
    public $sql;

    /**
     * @var array
     */
    public $bindings;
    
    public function __construct($sql, array $bindings, $previous){
        parent::__construct('', 0, $previous);

        $this->sql = $sql;
        $this->bindings = $bindings;
        $this->previous = $previous;
        $this->code = $previous->getCode();
        $this->message = $previous->getMessage() . ' (SQL: ' . SqlHelper::bind($sql, $bindings).')';

        if ($previous instanceof \PDOException) {
            $this->errorInfo = $previous->errorInfo;
        }
    }

    /**
     * 在网页版报错页面会展示此信息
     * @return string
     */
    public function getDescription(){
        return '<code>' . SqlHelper::bind($this->sql, $this->bindings) . '</code>';
    }
}