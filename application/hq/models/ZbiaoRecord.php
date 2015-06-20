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
        foreach ($data as $key => $value)
        {
            foreach ($value as $v)
            {
                $v = $date ? date('m月d日', $v) : intval($v);
                array_unshift($chat_array, $v);
            }
        }
        return $chat_array;
    }
}