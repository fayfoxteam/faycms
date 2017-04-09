<?php
namespace fay\core\db;

use fay\core\ErrorException;

class Exception extends ErrorException{
    /**
     * @var string
     */
    private $description;
    
    public function __construct($message = '', $description = '', $code = E_USER_ERROR, $filename = __FILE__, $lineno = __LINE__, $severity = 1, \Exception $previous = null){
        parent::__construct($message, $code, $severity, $filename, $lineno, $previous);
        $this->description = $description;
    }
    
    /**
     * 数据库异常，description基本是sql，加上<code>标签
     * @return string
     */
    public function getDescription(){
        return $this->description ? '<code>' . $this->description . '</code>' : '';
    }
    
    public function __toString(){
        return parent::__toString() . PHP_EOL .
            'SQL: ' . $this->description;
    }
}