<?php
namespace hq\models;

use fay\core\Model;
use fay\helpers\Date;
use hq\models\tables\Zbiaos;
use hq\models\tables\ZbiaoRecords;

class ZbiaoRecord extends Model
{
    /**
     * @return ZbiaoRecord
     */
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    
    public function insertRecord($data)
    {
        foreach ($data as $key => $value)
        {
            $id = mb_substr($key, 5);
            $table = Zbiaos::model()->find($id);
            if (!$value)
            {
                continue;
            }
            $day_use = $value - $table['zongzhi'];
            $records = array(
                'biao_id' => $table['biao_id'],
                'zongliang' => $value,
                'day_use' => $day_use,
                'week_num' => date('W'),
                'month_num' => date('n'),
                'created' => time(),
            );
            ZbiaoRecords::model()->insert($records);
            $biaos_update = array(
                'zongzhi' => $value,
                'updated' => time(),
            );
            Zbiaos::model()->update($biaos_update, $id);
        }
    }

    /**
     * @param $data
     * @param bool $date 是否是输出日期格式
     * @return array
     */
    public static function getChatData($data, $date = false)
    {
        $chat_array = [];
        foreach ($data as $key => $value) {
            foreach ($value as $v) {
                $v = $date ? date('m月d日', $v) : intval($v);
                array_unshift($chat_array, $v);
            }
        }
        return $chat_array;
    }


    /**
     * 拼接月份
     * @param $data
     * @return array 按月排序的数组
     */
    public static function getChatDataByMonth($data, $month = true)
    {
        $chat_array = [];
        foreach ($data as $key => $value) {
            if ($month) {
                $v = $value['month_num'] . '月';
            } else {
                $v = $value['week_num'] . '周';
            }
            $chat_array[] = $v;
        }

        return $chat_array;
    }

    public static function getBiaoName($biao_id)
    {
        return Zbiaos::model()->fetchRow(['biao_id = ? ' => $biao_id]);
    }
}