<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Gg coltd sets table model
 * 
 * @property int $id Id
 * @property string $key Key
 * @property string $value Value
 * @property int $updated_ip Updated Ip
 * @property string $updated_at 更新时间
 * @property int $created_ip Created Ip
 * @property string $created_at 创建时间
 * @property string $deleted_at 删除时间
 */
class GgColtdSetsTable extends Table{
    protected $_name = 'gg_coltd_sets';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id', 'updated_ip', 'created_ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('key'), 'string', array('max'=>32)),
            array(array('value'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'key'=>'Key',
            'value'=>'Value',
            'updated_ip'=>'Updated Ip',
            'updated_at'=>'更新时间',
            'created_ip'=>'Created Ip',
            'created_at'=>'创建时间',
            'deleted_at'=>'删除时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'key'=>'trim',
            'value'=>'trim',
            'updated_ip'=>'intval',
            'updated_at'=>'',
            'created_ip'=>'intval',
            'created_at'=>'',
            'deleted_at'=>'',
        );
    }
}