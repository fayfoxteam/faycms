<?php
namespace fay\exceptions;

/**
 * 访问权限异常
 */
class AccessDeniedHttpException extends HttpException{
    public function __construct($message = '', \Exception $previous = null, $code = E_USER_ERROR){
        parent::__construct($message, 403, $previous, $code);
    }
}