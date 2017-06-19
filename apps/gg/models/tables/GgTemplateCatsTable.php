<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Gg template cats table model
 *
 * @property int $id Id
 * @property int $pid Pid
 * @property string $name Name
 * @property int $sort Sort
 * @property int $updated_ip Updated Ip
 * @property string $updated_at 更新时间
 * @property int $created_ip Created Ip
 * @property string $created_at 创建时间
 * @property string $deleted_at Deleted At
 * @property int $is_show Is Show
 */
class GgTemplateCatsTable extends Table{
    protected $_name = 'gg_template_cats';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('updated_ip', 'created_ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id', 'pid'), 'int', array('min'=>-32768, 'max'=>32767)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('name'), 'string', array('max'=>32)),
            array(array('is_show'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'pid'=>'Pid',
            'name'=>'Name',
            'sort'=>'Sort',
            'updated_ip'=>'Updated Ip',
            'updated_at'=>'更新时间',
            'created_ip'=>'Created Ip',
            'created_at'=>'创建时间',
            'deleted_at'=>'Deleted At',
            'is_show'=>'Is Show',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'pid'=>'intval',
            'name'=>'trim',
            'sort'=>'intval',
            'updated_ip'=>'intval',
            'updated_at'=>'',
            'created_ip'=>'intval',
            'created_at'=>'',
            'deleted_at'=>'',
            'is_show'=>'intval',
        );
    }
}