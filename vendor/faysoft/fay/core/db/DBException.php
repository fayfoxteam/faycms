<?php
namespace fay\core\db;

class DBException extends \ErrorException{
    public function __construct($message = '', \Exception $previous = null){
        parent::__construct($message, E_USER_ERROR, 1, __FILE__, __LINE__, $previous);
    }
}