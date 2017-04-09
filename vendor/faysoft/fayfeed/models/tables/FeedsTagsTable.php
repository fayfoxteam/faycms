<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Feeds Tags model
 * 
 * @property int $feed_id 动态ID
 * @property int $tag_id 标签ID
 */
class FeedsTagsTable extends Table{
    protected $_name = 'feeds_tags';
    protected $_primary = array('feed_id', 'tag_id');
    
    /**
     * @param string $class_name
     * @return FeedsTagsTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('feed_id', 'tag_id'), 'int', array('min'=>0, 'max'=>4294967295)),
        );
    }

    public function labels(){
        return array(
            'feed_id'=>'动态ID',
            'tag_id'=>'标签ID',
        );
    }

    public function filters(){
        return array(
            'feed_id'=>'intval',
            'tag_id'=>'intval',
        );
    }
}