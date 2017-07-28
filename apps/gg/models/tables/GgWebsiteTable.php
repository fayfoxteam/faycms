<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * web站点表
 *
 * @property int $id Id
 * @property int $cat_sid 行业分类
 * @property int $cat_bid 模板分类
 * @property int $merchant_id 所属管理员站点（只关联主账号）
 * @property string $domain 自动生成的二级域名前缀
 * @property string $name 站点名称
 * @property int $thumbnail 网站缩略图
 * @property int $status 站点状态:0关闭,1未发布,2已发布
 * @property int $is_enable 是否开启:0代表欠费关闭的站点
 * @property string $apk apk下载地址
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class GgWebsiteTable extends Table{
    protected $_name = 'gg_website';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('thumbnail'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('id', 'cat_sid', 'cat_bid', 'merchant_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('domain'), 'string', array('max'=>30)),
            array(array('name'), 'string', array('max'=>32)),
            array(array('apk'), 'string', array('max'=>255)),
            array(array('is_enable'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'cat_sid'=>'行业分类',
            'cat_bid'=>'模板分类',
            'merchant_id'=>'所属管理员站点（只关联主账号）',
            'domain'=>'自动生成的二级域名前缀',
            'name'=>'站点名称',
            'thumbnail'=>'网站缩略图',
            'status'=>'站点状态:0关闭,1未发布,2已发布',
            'is_enable'=>'是否开启:0代表欠费关闭的站点',
            'apk'=>'apk下载地址',
            'created_at'=>'创建时间',
            'updated_at'=>'更新时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'cat_sid'=>'intval',
            'cat_bid'=>'intval',
            'merchant_id'=>'intval',
            'domain'=>'trim',
            'name'=>'trim',
            'thumbnail'=>'intval',
            'status'=>'intval',
            'is_enable'=>'intval',
            'apk'=>'trim',
            'created_at'=>'',
            'updated_at'=>'',
        );
    }
}