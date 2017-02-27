<?php
namespace guangong\helpers;

use fay\helpers\NumberHelper;
use guangong\models\tables\GuangongArmsTable;
use guangong\models\tables\GuangongUserExtraTable;

class UserHelper{
	public static function getCode($user_id){
		$extra = GuangongUserExtraTable::model()->find($user_id, 'arm_id');
		if(!$extra['arm_id']){
			return '';
		}
		
		$arm = GuangongArmsTable::model()->find($extra['arm_id'], 'name');
		
		return '关羽军团' . $arm['name'] . '营' . $user_id;
	}
}