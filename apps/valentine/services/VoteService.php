<?php
namespace valentine\services;

use fay\core\Loader;
use fay\core\Service;

class VoteService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 给用户组投票
     * @param int $team_id 组ID
     */
    public function vote($team_id){
        
    }
}