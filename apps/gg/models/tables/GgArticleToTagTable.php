<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 文章标签关系表
 * 
 * @property int $article_id Article Id
 * @property int $tag_id Tag Id
 */
class GgArticleToTagTable extends Table{
    protected $_name = 'gg_article_to_tag';
    protected $_primary = array('article_id', 'tag_id');
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('article_id', 'tag_id'), 'int', array('min'=>0, 'max'=>4294967295)),
        );
    }

    public function labels(){
        return array(
            'article_id'=>'Article Id',
            'tag_id'=>'Tag Id',
        );
    }

    public function filters(){
        return array(
            'article_id'=>'intval',
            'tag_id'=>'intval',
        );
    }
}