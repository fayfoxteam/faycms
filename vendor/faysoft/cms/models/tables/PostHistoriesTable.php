<?php
namespace cms\models\tables;

use fay\core\db\Table;

/**
 * 文章历史版本存档
 * 
 * @property int $id Id
 * @property int $post_id 文章ID
 * @property string $title 标题
 * @property string $content 正文
 * @property int $content_type 正文类型（普通文本，符文本，markdown）
 * @property int $cat_id 分类ID
 * @property int $thumbnail 缩略图
 * @property string $abstract 摘要
 * @property int $user_id 用户ID
 * @property int $create_time 创建时间
 * @property int $ip_int IP
 */
class PostHistoriesTable extends Table{
    protected $_name = 'post_histories';
    
    /**
     * @param string $class_name
     * @return PostHistoriesTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('id', 'post_id', 'thumbnail', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('content_type'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('title', 'abstract'), 'string', array('max'=>500)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'post_id'=>'文章ID',
            'title'=>'标题',
            'content'=>'正文',
            'content_type'=>'正文类型（普通文本，符文本，markdown）',
            'cat_id'=>'分类ID',
            'thumbnail'=>'缩略图',
            'abstract'=>'摘要',
            'user_id'=>'用户ID',
            'create_time'=>'创建时间',
            'ip_int'=>'IP',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'post_id'=>'intval',
            'title'=>'trim',
            'content'=>'',
            'content_type'=>'intval',
            'cat_id'=>'intval',
            'thumbnail'=>'intval',
            'abstract'=>'trim',
            'user_id'=>'intval',
        );
    }
    
    public function getNotWritableFields($scene){
        switch($scene){
            case 'insert':
                return array('id');
            default:
                return array();
        }
    }
}