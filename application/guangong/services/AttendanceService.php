<?php
namespace guangong\services;

use fay\core\ErrorException;
use fay\core\Exception;
use fay\core\Service;
use fay\services\user\UserService;
use guangong\models\tables\GuangongAttendancesTable;

class AttendanceService extends Service{
	/**
	 * @param string $class_name
	 * @return AttendanceService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 出勤（完成所有当日任务，算一次出勤）
	 * @param null|int $user_id 用户ID，默认为当前登录用户ID
	 * @throws ErrorException
	 * @throws Exception
	 */
	public function attend($user_id = null){
		if($user_id === null){
			$user_id = \F::app()->current_user;
		}else if(!UserService::isUserIdExist($user_id)){
			throw new ErrorException('指定用户ID不存在', 'user-id-is-not-exist');
		}
		
		//获取最后一条出勤记录
		$last_attendance = GuangongAttendancesTable::model()->fetchRow(
			array(
				'user_id = ?'=>$user_id,
			),
			'create_date,continuous',
			'id DESC'
		);
		
		//连续出勤天数
		$continuous = 1;
		if($last_attendance){
			//曾经出勤过
			if($last_attendance['create_date'] == date('Y-m-d', \F::app()->current_time)){
				throw new Exception('您今天已经出勤过了，欢迎明天继续', 'already-attended');
			}
			
			if($last_attendance['create_date'] == date('Y-m-d', \F::app()->current_time - 86400)){
				//最后一条出勤记录是昨天
				$continuous = $last_attendance['continuous'] + 1;
			}
		}
		
		//插入出勤记录
		GuangongAttendancesTable::model()->insert(array(
			'user_id'=>$user_id,
			'create_date'=>date('Y-m-d', \F::app()->current_time),
			'create_time'=>\F::app()->current_time,
			'continuous'=>$continuous,
		));
		
		//更新用户军衔
		RankService::service()->update($user_id);
	}
	
	/**
	 * 获取总出勤次数
	 * @param null|int $user_id
	 * @throws ErrorException
	 */
	public function getCount($user_id = null){
		if($user_id === null){
			$user_id = \F::app()->current_user;
		}else if(!UserService::isUserIdExist($user_id)){
			throw new ErrorException('指定用户ID不存在', 'user-id-is-not-exist');
		}
		
		$attendances = GuangongAttendancesTable::model()->fetchRow(array(
			'user_id = ?'=>$user_id,
		), 'COUNT(*)');
		
		return $attendances['COUNT(*)'];
	}
}