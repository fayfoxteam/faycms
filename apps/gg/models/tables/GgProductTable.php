<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 商品表
 *
 * @property int $id Id
 * @property int $merchant_id 所属管理员站点（只关联主账号）
 * @property int $website_id 网站ID
 * @property int $cat_id 分类ID
 * @property string $title 商品标题
 * @property int $thumbnail 商品图片
 * @property string $abstract 描述
 * @property string $seo_title Seo Title
 * @property string $seo_keywords Seo Keywords
 * @property string $seo_description Seo Description
 * @property int $is_comment 是否评论
 * @property int $is_recommended 是否推荐  1是   0否
 * @property int $sort Sort
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 * @property string $deleted_at Deleted At
 * @property float $price 价格
 */
class GgProductTable extends Table{
    protected $_name = 'gg_product';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('id', 'thumbnail'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('merchant_id', 'website_id', 'cat_id', 'sort'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('title', 'seo_title', 'seo_keywords', 'seo_description'), 'string', array('max'=>255)),
            array(array('price'), 'float', array('length'=>10, 'decimal'=>2)),
            array(array('is_comment', 'is_recommended'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'merchant_id'=>'所属管理员站点（只关联主账号）',
            'website_id'=>'网站ID',
            'cat_id'=>'分类ID',
            'title'=>'商品标题',
            'thumbnail'=>'商品图片',
            'abstract'=>'描述',
            'seo_title'=>'Seo Title',
            'seo_keywords'=>'Seo Keywords',
            'seo_description'=>'Seo Description',
            'is_comment'=>'是否评论',
            'is_recommended'=>'是否推荐  1是   0否',
            'sort'=>'Sort',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
            'deleted_at'=>'Deleted At',
            'price'=>'价格',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'merchant_id'=>'intval',
            'website_id'=>'intval',
            'cat_id'=>'intval',
            'title'=>'trim',
            'thumbnail'=>'intval',
            'abstract'=>'',
            'seo_title'=>'trim',
            'seo_keywords'=>'trim',
            'seo_description'=>'trim',
            'is_comment'=>'intval',
            'is_recommended'=>'intval',
            'sort'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
            'deleted_at'=>'',
            'price'=>'floatval',
        );
    }
}