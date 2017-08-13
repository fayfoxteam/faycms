<?php
namespace valentine\services;

use fay\core\Loader;
use fay\core\Service;

class MatchService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 为指定用户匹配用户
     * @param null|int $user_id 默认为当前用户
     */
    public function match($user_id = null){
        
    }
}