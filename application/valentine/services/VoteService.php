<?php
namespace valentine\services;

use fay\core\Service;

class VoteService extends Service{
	/**
	 * @param string $class_name
	 * @return VoteService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 给用户组投票
	 * @param int $team_id 组ID
	 */
	public function vote($team_id){
		
	}
}