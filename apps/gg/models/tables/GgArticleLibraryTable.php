<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 文章库表
 *
 * @property int $id Id
 * @property int $cat_id 文章分类
 * @property string $title 标题
 * @property string $content 内容
 * @property int $thumbnail 图片
 * @property string $source 来源
 * @property string $source_url 文章原网址
 * @property string $author 作者
 * @property string $abstract 文章摘要
 * @property string $seo_title SEO标题
 * @property string $seo_keywords SEO关键词
 * @property string $seo_description SEO描述
 * @property int $sort 排序
 * @property int $is_recommended 是否推荐 0否  1是
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 * @property string $deleted_at 删除时间
 */
class GgArticleLibraryTable extends Table{
    protected $_name = 'gg_article_library';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('id', 'thumbnail', 'sort'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('title', 'source', 'author'), 'string', array('max'=>50)),
            array(array('source_url', 'seo_title', 'seo_keywords', 'seo_description'), 'string', array('max'=>255)),
            array(array('abstract'), 'string', array('max'=>1000)),
            array(array('is_recommended'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'cat_id'=>'文章分类',
            'title'=>'标题',
            'content'=>'内容',
            'thumbnail'=>'图片',
            'source'=>'来源',
            'source_url'=>'文章原网址',
            'author'=>'作者',
            'abstract'=>'文章摘要',
            'seo_title'=>'SEO标题',
            'seo_keywords'=>'SEO关键词',
            'seo_description'=>'SEO描述',
            'sort'=>'排序',
            'is_recommended'=>'是否推荐 0否  1是',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
            'deleted_at'=>'删除时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'cat_id'=>'intval',
            'title'=>'trim',
            'content'=>'',
            'thumbnail'=>'intval',
            'source'=>'trim',
            'source_url'=>'trim',
            'author'=>'trim',
            'abstract'=>'trim',
            'seo_title'=>'trim',
            'seo_keywords'=>'trim',
            'seo_description'=>'trim',
            'sort'=>'intval',
            'is_recommended'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
            'deleted_at'=>'',
        );
    }
}