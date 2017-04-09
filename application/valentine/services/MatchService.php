<?php
namespace valentine\services;

use fay\core\ErrorException;
use fay\core\Service;
use fay\services\user\UserService;

class MatchService extends Service{
    /**
     * @param string $class_name
     * @return MatchService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    /**
     * 为指定用户匹配用户
     * @param null|int $user_id 默认为当前用户
     * @throws ErrorException
     */
    public function match($user_id = null){
        if($user_id === null){
            $user_id = \F::app()->current_user;
        }else if(!UserService::isUserIdExist($user_id)){
            throw new ErrorException('指定用户ID不存在', 'user-id-is-not-exist');
        }
        
        
    }
}