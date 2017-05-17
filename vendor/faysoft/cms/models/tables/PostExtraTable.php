<?php
namespace cms\models\tables;

use fay\core\db\Table;

/**
 * Post Extra model
 * 
 * @property int $post_id 文章ID
 * @property string $seo_title SEO Title
 * @property string $seo_keywords SEO Keywords
 * @property string $seo_description SEO Description
 * @property string $markdown Markdown文本
 * @property int $ip_int IP
 * @property string $source 来源
 * @property string $source_link 来源链接
 */
class PostExtraTable extends Table{
    protected $_name = 'post_extra';
    protected $_primary = 'post_id';
    
    /**
     * @param string $class_name
     * @return PostExtraTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('post_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('seo_title', 'seo_keywords', 'source_link'), 'string', array('max'=>255)),
            array(array('seo_description'), 'string', array('max'=>500)),
            array(array('source'), 'string', array('max'=>50)),
            
            array(array('source_link'), 'url')
        );
    }

    public function labels(){
        return array(
            'post_id'=>'文章ID',
            'seo_title'=>'SEO Title',
            'seo_keywords'=>'SEO Keywords',
            'seo_description'=>'SEO Description',
            'markdown'=>'Markdown文本',
            'ip_int'=>'IP',
            'source'=>'来源',
            'source_link'=>'来源链接',
        );
    }

    public function filters(){
        return array(
            'post_id'=>'intval',
            'seo_title'=>'trim',
            'seo_keywords'=>'trim',
            'seo_description'=>'trim',
            'markdown'=>'',
            'source'=>'trim',
            'source_link'=>'trim',
        );
    }
    
    public function getNotWritableFields($scene){
        switch($scene){
            case 'insert':
                return array();
            case 'update':
            default:
                return array(
                    'post_id', 'ip_int'
                );
        }
    }
}