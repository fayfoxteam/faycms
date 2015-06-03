<?php
namespace hq\models;

use fay\core\Model;
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
            $id = substr($key, -1);
            $table = Zbiaos::model()->find($id);
            $day_use = $value - $table['zongzhi'];
            $records = array(
                'biao_id' => $table['biao_id'],
                'zongliang' => $value,
                'day_use' => $day_use,
                'created' => time(),
            );
            dump($records);
            ZbiaoRecords::model()->insert($records);
            $biaos_update = array(
                'zongzhi' => $value,
                'updated' => time(),
            );
            Zbiaos::model()->update($biaos_update, $id);
        }
    }
}