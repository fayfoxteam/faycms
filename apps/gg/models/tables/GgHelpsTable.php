<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Gg helps table model
 * 
 * @property int $id Id
 * @property int $cat_id Cat Id
 * @property string $name Name
 * @property string $content Content
 * @property int $sort 排序值
 * @property int $updated_ip Updated Ip
 * @property string $updated_at 更新时间
 * @property int $created_ip Created Ip
 * @property string $created_at 创建时间
 * @property string $deleted_at 删除时间
 */
class GgHelpsTable extends Table{
    protected $_name = 'gg_helps';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('updated_ip', 'created_ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id', 'cat_id', 'sort'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('name'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'cat_id'=>'Cat Id',
            'name'=>'Name',
            'content'=>'Content',
            'sort'=>'排序值',
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
            'cat_id'=>'intval',
            'name'=>'trim',
            'content'=>'',
            'sort'=>'intval',
            'updated_ip'=>'intval',
            'updated_at'=>'',
            'created_ip'=>'intval',
            'created_at'=>'',
            'deleted_at'=>'',
        );
    }
}