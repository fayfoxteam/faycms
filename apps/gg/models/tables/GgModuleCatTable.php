<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 用户编辑的页面表
 * 
 * @property int $id 自增ID
 * @property string $name 模块名称
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 * @property int $sort 排序 从小到大
 */
class GgModuleCatTable extends Table{
    protected $_name = 'gg_module_cat';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('name'), 'string', array('max'=>50)),
        );
    }

    public function labels(){
        return array(
            'id'=>'自增ID',
            'name'=>'模块名称',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
            'sort'=>'排序 从小到大',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'updated_at'=>'',
            'created_at'=>'',
            'sort'=>'intval',
        );
    }
}