<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

class PostsTagsTable extends Table{
    protected $_name = 'posts_tags';
    protected $_primary = array('post_id', 'tag_id');
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('post_id', 'tag_id'), 'int', array('min'=>0, 'max'=>4294967295)),
        );
    }

    public function labels(){
        return array(
            'post_id'=>'Post Id',
            'tag_id'=>'Tag Id',
        );
    }

    public function filters(){
        return array(
            'post_id'=>'intval',
            'tag_id'=>'intval',
        );
    }
}