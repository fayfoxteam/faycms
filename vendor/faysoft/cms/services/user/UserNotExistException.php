<?php
namespace cms\services\user;

/**
 * 指定用户不存在
 */
class UserNotExistException extends \UnexpectedValueException{
    public function __construct($user_id = 0, \Exception $previous = null){
        parent::__construct(
            $user_id ? "指定用户ID[{$user_id}]不存在" : '指定用户不存在',
            0,
            $previous
        );
    }
}