<?php
namespace hq\models;

use fay\core\Model;
use fay\core\Sql;
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
    
    public function insertRecord($data, $time = null)
    {
        $created = $time ? strtotime($time) : time();
        $week_num = date('W', $created);
        $month_num = date('n', $created);
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
                'parent_id' => $table['parent_id'],
                'zongliang' => $value,
                'day_use' => $day_use * $table['times'],
                'week_num' => $week_num,
                'month_num' => $month_num,
                'created' => $created,
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

    /**
     * 根据表id获取变比
     * @param $biao_id
     */
    public static function getTimes($biao_id)
    {
        return Zbiaos::model()->fetchRow(['biao_id = ?' => $biao_id], 'times');
    }

    /**
     * 根据传入时间跟记录表里面时间最大值进行比较
     * @param null $created
     * @return bool
     */
    public function verifyCreatedTime($created = null, $type_id = 1)
    {
        if (!$created) {
            $created = time();
        }
        $sql = new Sql();
        $max_id = $sql->from('zbiaos', 'zbiaos', 'max(biao_id) as max_id')->where(['type = ?' => $type_id])->fetchRow();

        $data = $sql->from('zbiao_records', 'records', 'max(created) as max_created')->where(['biao_id <= ?' => $max_id])->fetchRow();
        $max_created = $data['max_created'];

        return $created > $max_created ? true : false;
    }
}