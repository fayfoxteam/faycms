<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Feed Meta model
 * 
 * @property int $feed_id 动态ID
 * @property int $comments 评论数
 * @property int $real_comments 真实评论数
 * @property int $likes 点赞数
 * @property int $real_likes 真实点赞数
 */
class FeedMetaTable extends Table{
    protected $_name = 'feed_meta';
    protected $_primary = 'feed_id';
    
    /**
     * @param string $class_name
     * @return FeedMetaTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('feed_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('likes', 'real_likes', 'favorites', 'real_favorites'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('comments', 'real_comments'), 'int', array('min'=>0, 'max'=>65535)),
        );
    }

    public function labels(){
        return array(
            'feed_id'=>'动态ID',
            'comments'=>'评论数',
            'real_comments'=>'真实评论数',
            'likes'=>'点赞数',
            'real_likes'=>'真实点赞数',
            'favorites'=>'收藏数',
            'real_favorites'=>'真实收藏数',
        );
    }

    public function filters(){
        return array(
            'feed_id'=>'intval',
            'comments'=>'intval',
            'likes'=>'intval',
            'favorites'=>'intval',
        );
    }
    
    public function getNotWritableFields($scene){
        switch($scene){
            case 'insert':
                return array(
                    'comments', 'real_comments',
                    'likes', 'real_likes',
                    'favorites', 'real_favorites',
                );
            case 'update':
            default:
                return array(
                    'feed_id',
                    'comments', 'real_comments',
                    'likes', 'real_likes',
                    'favorites', 'real_favorites',
                );
        }
    }
}