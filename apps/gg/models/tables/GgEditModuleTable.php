<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 高级编辑器存的数据表
 *
 * @property int $id 自增ID
 * @property int $page_id 页面ID
 * @property int $website_id 网站ID
 * @property int $cat_id 分类id
 * @property string $name 模块名称
 * @property int $thumbnail 封面图片
 * @property string $html json数据
 * @property int $sort 排序
 * @property int $is_enable 是否启用:1启用,0关闭
 * @property int $is_public 1公共模块,0不是
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 * @property string $deleted_at Deleted At
 * @property string $module_id Module Id
 */
class GgEditModuleTable extends Table{
    protected $_name = 'gg_edit_module';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('id', 'page_id', 'thumbnail'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('website_id', 'cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('name', 'module_id'), 'string', array('max'=>50)),
            array(array('is_enable', 'is_public'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'自增ID',
            'page_id'=>'页面ID',
            'website_id'=>'网站ID',
            'cat_id'=>'分类id',
            'name'=>'模块名称',
            'thumbnail'=>'封面图片',
            'html'=>'json数据',
            'sort'=>'排序',
            'is_enable'=>'是否启用:1启用,0关闭',
            'is_public'=>'1公共模块,0不是',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
            'deleted_at'=>'Deleted At',
            'module_id'=>'Module Id',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'page_id'=>'intval',
            'website_id'=>'intval',
            'cat_id'=>'intval',
            'name'=>'trim',
            'thumbnail'=>'intval',
            'html'=>'',
            'sort'=>'intval',
            'is_enable'=>'intval',
            'is_public'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
            'deleted_at'=>'',
            'module_id'=>'trim',
        );
    }
}