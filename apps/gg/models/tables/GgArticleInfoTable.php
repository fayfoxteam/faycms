<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 文章详情表
 * 
 * @property int $article_id Article Id
 * @property string $description 商品详细
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 */
class GgArticleInfoTable extends Table{
    protected $_name = 'gg_article_info';
    protected $_primary = 'article_id';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('article_id'), 'int', array('min'=>0, 'max'=>4294967295)),
        );
    }

    public function labels(){
        return array(
            'article_id'=>'Article Id',
            'description'=>'商品详细',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
        );
    }

    public function filters(){
        return array(
            'article_id'=>'intval',
            'description'=>'',
            'updated_at'=>'',
            'created_at'=>'',
        );
    }
}