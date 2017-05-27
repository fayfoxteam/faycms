<?php
namespace fayfeed\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

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
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
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