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
                return '最牛组合名';
            break;
            case ValentineUserTeamsTable::TYPE_ORIGINALITY:
                return '最佳创意照';
            break;
            case ValentineUserTeamsTable::TYPE_BLESSING:
                return '最美祝福语';
            break;
            default:
                return '';
        }
    }
}