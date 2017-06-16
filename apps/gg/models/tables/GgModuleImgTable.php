<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 用户编辑的页面表
 * 
 * @property int $id 自增ID
 * @property int $module_id 所属网站id
 * @property string $name 模块名称
 * @property string $src 模块类型
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 */
class GgModuleImgTable extends Table{
    protected $_name = 'gg_module_img';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('module_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('name'), 'string', array('max'=>50)),
            array(array('src'), 'string', array('max'=>100)),
        );
    }

    public function labels(){
        return array(
            'id'=>'自增ID',
            'module_id'=>'所属网站id',
            'name'=>'模块名称',
            'src'=>'模块类型',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'module_id'=>'intval',
            'name'=>'trim',
            'src'=>'trim',
            'updated_at'=>'',
            'created_at'=>'',
        );
    }
}