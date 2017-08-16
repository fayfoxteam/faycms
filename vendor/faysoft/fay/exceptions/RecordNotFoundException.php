<?php
namespace fay\exceptions;

/**
 * 数据库记录未找到异常基类。当数据库字段引用关系不存在时，抛出此异常
 */
class RecordNotFoundException extends HttpException{
    public function __construct($message, \Exception $previous = null){
        parent::__construct($message, 422, $previous);
    }
}