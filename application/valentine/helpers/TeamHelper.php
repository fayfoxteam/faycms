<?php
namespace valentine\helpers;

use valentine\models\tables\ValentineUserTeamsTable;

class TeamHelper{
	/**
	 * 获取组合类型描述
	 * @param int $type
	 * @return string
	 */
	public static function getTypeTitle($type){
		switch($type){
			case ValentineUserTeamsTable::TYPE_COUPLE:
				return '最具夫妻相';
			break;
			case ValentineUserTeamsTable::TYPE_ORIGINALITY:
				return '最佳创意奖';
			break;
			case ValentineUserTeamsTable::TYPE_BLESSING:
				return '最赞祝福语';
			break;
			default:
				return '';
		}
	}
}