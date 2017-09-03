<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 模块表
 *
 * @property int $id 自增ID
 * @property int $cat_bid Cat Bid
 * @property int $cat_sid Cat Sid
 * @property int $cat_id 模块分类
 * @property int $website_id 网站ID
 * @property string $name 模块名称
 * @property string $intro 简介
 * @property string $html Html
 * @property int $thumbnail 缩略图
 * @property int $device_type 页面类型:1mobile,2pc
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 * @property string $deleted_at Deleted At
 */
class GgModuleTable extends Table{
    protected $_name = 'gg_module';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('thumbnail'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('id', 'cat_bid', 'cat_sid', 'cat_id', 'website_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('device_type'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('name'), 'string', array('max'=>50)),
        );
    }

    public function labels(){
        return array(
            'id'=>'自增ID',
            'cat_bid'=>'Cat Bid',
            'cat_sid'=>'Cat Sid',
            'cat_id'=>'模块分类',
            'website_id'=>'网站ID',
            'name'=>'模块名称',
            'intro'=>'简介',
            'html'=>'Html',
            'thumbnail'=>'缩略图',
            'device_type'=>'页面类型:1mobile,2pc',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
            'deleted_at'=>'Deleted At',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'cat_bid'=>'intval',
            'cat_sid'=>'intval',
            'cat_id'=>'intval',
            'website_id'=>'intval',
            'name'=>'trim',
            'intro'=>'',
            'html'=>'',
            'thumbnail'=>'intval',
            'device_type'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
            'deleted_at'=>'',
        );
    }
}