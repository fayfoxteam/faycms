<?php
namespace valentine\services;

use fay\core\Loader;
use fay\core\Service;
use valentine\models\tables\ValentineConstellationsTable;

/**
 * 星座
 */
class ConstellationService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 根据生日获取星座
     * @param string $birthday 生日（strtotime能识别的日期格式均可）
     * @return array
     * @throws \ErrorException
     */
    public function getByBirthday($birthday){
        $birthday_timestamp = strtotime($birthday);
        if(!$birthday_timestamp){
            throw new \ErrorException('生日日期格式错误');
        }
        $month = date('n', $birthday_timestamp);
        $date = date('j', $birthday_timestamp);
        
        return ValentineConstellationsTable::model()->fetchRow(array(
            'or'=>array(
                "start_month = {$month} AND start_date <= {$date}",
                "end_month = {$month} AND end_date >= {$date}",
            )
        ), 'id,name');
    }
}