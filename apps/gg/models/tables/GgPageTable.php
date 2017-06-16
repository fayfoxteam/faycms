<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 用户编辑的页面表
 *
 * @property int $id 自增ID
 * @property int $website_id 网站ID
 * @property int $device_type 设备标识:1mobile,2pc
 * @property int $type 类型:page单页,article,info,article_detail
 * @property string $name 页面名称
 * @property string $category 页面类型
 * @property string $url URL地址
 * @property int $thumbnail 页面展示图
 * @property string $describe 页面描述
 * @property string $seo_title Seo Title
 * @property string $seo_keywords SEO页面关键词
 * @property string $seo_description SEO页面说明
 * @property int $is_page 是否单一页面
 * @property int $is_public 是否公开:0不公开,1公开
 * @property int $is_enable 是否启用:1启用,0关闭
 * @property int $is_home 是否首页:1代表是,0代表不是
 * @property float $price 价格
 * @property string $global 页面的全局配置
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 * @property string $deleted_at Deleted At
 */
class GgPageTable extends Table{
    protected $_name = 'gg_page';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('id', 'thumbnail'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('website_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('device_type', 'type'), 'int', array('min'=>0, 'max'=>255)),
            array(array('name', 'category', 'url'), 'string', array('max'=>50)),
            array(array('describe'), 'string', array('max'=>250)),
            array(array('seo_title', 'seo_keywords', 'seo_description'), 'string', array('max'=>255)),
            array(array('price'), 'float', array('length'=>10, 'decimal'=>2)),
            array(array('is_page', 'is_public', 'is_enable', 'is_home'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'自增ID',
            'website_id'=>'网站ID',
            'device_type'=>'设备标识:1mobile,2pc',
            'type'=>'类型:page单页,article,info,article_detail',
            'name'=>'页面名称',
            'category'=>'页面类型',
            'url'=>'URL地址',
            'thumbnail'=>'页面展示图',
            'describe'=>'页面描述',
            'seo_title'=>'Seo Title',
            'seo_keywords'=>'SEO页面关键词',
            'seo_description'=>'SEO页面说明',
            'is_page'=>'是否单一页面',
            'is_public'=>'是否公开:0不公开,1公开',
            'is_enable'=>'是否启用:1启用,0关闭',
            'is_home'=>'是否首页:1代表是,0代表不是',
            'price'=>'价格',
            'global'=>'页面的全局配置',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
            'deleted_at'=>'Deleted At',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'website_id'=>'intval',
            'device_type'=>'intval',
            'type'=>'intval',
            'name'=>'trim',
            'category'=>'trim',
            'url'=>'trim',
            'thumbnail'=>'intval',
            'describe'=>'trim',
            'seo_title'=>'trim',
            'seo_keywords'=>'trim',
            'seo_description'=>'trim',
            'is_page'=>'intval',
            'is_public'=>'intval',
            'is_enable'=>'intval',
            'is_home'=>'intval',
            'price'=>'floatval',
            'global'=>'',
            'updated_at'=>'',
            'created_at'=>'',
            'deleted_at'=>'',
        );
    }
}