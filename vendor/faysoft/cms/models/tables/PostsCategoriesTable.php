<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

class PostsCategoriesTable extends Table{
    protected $_name = 'posts_categories';
    protected $_primary = array('post_id', 'cat_id');
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('post_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
        );
    }

    public function labels(){
        return array(
            'post_id'=>'Post Id',
            'cat_id'=>'Cat Id',
        );
    }

    public function filters(){
        return array(
            'post_id'=>'intval',
            'cat_id'=>'intval',
        );
    }
}