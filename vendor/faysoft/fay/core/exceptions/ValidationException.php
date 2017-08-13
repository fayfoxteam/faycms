<?php
namespace fay\core\exceptions;

/**
 * 表单数据格式异常
 */
class ValidationException extends HttpException{
    public function __construct($message, \Exception $previous = null){
        parent::__construct($message, 422, E_USER_ERROR, $previous);
    }
}