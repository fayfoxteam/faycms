<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 标签表
 *
 * @property int $id Id
 * @property string $name 标签名称
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class GgTagTable extends Table{
    protected $_name = 'gg_tag';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('name'), 'string', array('max'=>10)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'标签名称',
            'created_at'=>'创建时间',
            'updated_at'=>'更新时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'created_at'=>'',
            'updated_at'=>'',
        );
    }
}