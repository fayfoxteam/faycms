<?php
namespace faywiki\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 文档计数信息
 *
 * @property int $doc_id 文章ID
 * @property int $last_view_time 最后访问时间
 * @property int $views 阅读数
 * @property int $real_views 真实阅读数
 * @property int $likes 点赞数
 * @property int $real_likes 真实点赞数
 * @property int $favorites 收藏数
 * @property int $real_favorites 真实收藏数
 * @property int $shares 分享数
 * @property int $real_shares 真实分享数
 */
class WikiDocMetaTable extends Table{
    protected $_name = 'wiki_doc_meta';
    protected $_primary = 'doc_id';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('doc_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('views', 'real_views', 'likes', 'real_likes', 'favorites', 'real_favorites', 'shares', 'real_shares'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('last_view_time'), 'datetime'),
        );
    }

    public function labels(){
        return array(
            'doc_id'=>'文章ID',
            'last_view_time'=>'最后访问时间',
            'views'=>'阅读数',
            'real_views'=>'真实阅读数',
            'likes'=>'点赞数',
            'real_likes'=>'真实点赞数',
            'favorites'=>'收藏数',
            'real_favorites'=>'真实收藏数',
            'shares'=>'分享数',
            'real_shares'=>'真实分享数',
        );
    }

    public function filters(){
        return array(
            'doc_id'=>'intval',
            'last_view_time'=>'trim',
            'views'=>'intval',
            'real_views'=>'intval',
            'likes'=>'intval',
            'real_likes'=>'intval',
            'favorites'=>'intval',
            'real_favorites'=>'intval',
            'shares'=>'intval',
            'real_shares'=>'intval',
        );
    }
}