<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 装修之后的模块表
 *
 * @property int $id 自增ID
 * @property int $page_id 关联页面的id
 * @property int $website_id 所属网站id
 * @property string $type 内容类型
 * @property int $sort 排序
 * @property string $name 模块名称
 * @property string $model model标识
 * @property string $file 对应模块地址
 * @property string $data json数据
 * @property string $images Images
 * @property string $is_enable 是否启用  1启用   0关闭
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 */
class GgDesignTable extends Table{
    protected $_name = 'gg_design';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('page_id', 'website_id', 'sort'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('type', 'name', 'model'), 'string', array('max'=>50)),
            array(array('file'), 'string', array('max'=>250)),
            array(array('is_enable'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'自增ID',
            'page_id'=>'关联页面的id',
            'website_id'=>'所属网站id',
            'type'=>'内容类型',
            'sort'=>'排序',
            'name'=>'模块名称',
            'model'=>'model标识',
            'file'=>'对应模块地址',
            'data'=>'json数据',
            'images'=>'Images',
            'is_enable'=>'是否启用  1启用   0关闭',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'page_id'=>'intval',
            'website_id'=>'intval',
            'type'=>'trim',
            'sort'=>'intval',
            'name'=>'trim',
            'model'=>'trim',
            'file'=>'trim',
            'data'=>'',
            'images'=>'',
            'is_enable'=>'',
            'updated_at'=>'',
            'created_at'=>'',
        );
    }
}