<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 高级编辑器存的数据表
 * 
 * @property int $id 自增ID
 * @property int $website_id 网站ID
 * @property string $name 模块名称
 * @property int $sort 排序
 * @property int $is_enable 是否启用:1启用,0关闭
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 */
class GgEditModuleCatTable extends Table{
    protected $_name = 'gg_edit_module_cat';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('website_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('name'), 'string', array('max'=>50)),
            array(array('is_enable'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'自增ID',
            'website_id'=>'网站ID',
            'name'=>'模块名称',
            'sort'=>'排序',
            'is_enable'=>'是否启用:1启用,0关闭',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'website_id'=>'intval',
            'name'=>'trim',
            'sort'=>'intval',
            'is_enable'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
        );
    }
}