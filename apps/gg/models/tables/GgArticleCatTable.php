<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 文章分类
 *
 * @property int $id Id
 * @property int $website_id 网站ID
 * @property int $parent_id 父节点
 * @property int $left_value Left Value
 * @property int $right_value Right Value
 * @property string $name 分类名称
 * @property string $remark Remark
 * @property int $sort 排序
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 * @property string $deleted_at 删除时间
 */
class GgArticleCatTable extends Table{
    protected $_name = 'gg_article_cat';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('id', 'website_id', 'parent_id', 'left_value', 'right_value'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('name'), 'string', array('max'=>50)),
            array(array('remark'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'website_id'=>'网站ID',
            'parent_id'=>'父节点',
            'left_value'=>'Left Value',
            'right_value'=>'Right Value',
            'name'=>'分类名称',
            'remark'=>'Remark',
            'sort'=>'排序',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
            'deleted_at'=>'删除时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'website_id'=>'intval',
            'parent_id'=>'intval',
            'name'=>'trim',
            'remark'=>'trim',
            'sort'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
            'deleted_at'=>'',
        );
    }
}