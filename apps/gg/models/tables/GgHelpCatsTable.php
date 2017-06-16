<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Gg help cats table model
 * 
 * @property int $id Id
 * @property string $name Name
 * @property string $remark Remark
 * @property int $position Position
 * @property int $updated_ip Updated Ip
 * @property string $updated_at 更新时间
 * @property int $created_ip Created Ip
 * @property string $created_at 创建时间
 * @property string $deleted_at Deleted At
 */
class GgHelpCatsTable extends Table{
    protected $_name = 'gg_help_cats';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('updated_ip', 'created_ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id', 'position'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('name'), 'string', array('max'=>32)),
            array(array('remark'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'Name',
            'remark'=>'Remark',
            'position'=>'Position',
            'updated_ip'=>'Updated Ip',
            'updated_at'=>'更新时间',
            'created_ip'=>'Created Ip',
            'created_at'=>'创建时间',
            'deleted_at'=>'Deleted At',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'remark'=>'trim',
            'position'=>'intval',
            'updated_ip'=>'intval',
            'updated_at'=>'',
            'created_ip'=>'intval',
            'created_at'=>'',
            'deleted_at'=>'',
        );
    }
}