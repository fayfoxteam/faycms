<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Post Extra model
 * 
 * @property int $post_id 文章ID
 * @property string $seo_title SEO Title
 * @property string $seo_keywords SEO Keywords
 * @property string $seo_description SEO Descriotion
 * @property string $markdown Markdown文本
 * @property int $ip_int IP
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
            array(array('seo_title', 'seo_keywords'), 'string', array('max'=>255)),
            array(array('seo_description'), 'string', array('max'=>500)),
        );
    }

    public function labels(){
        return array(
            'post_id'=>'文章ID',
            'seo_title'=>'SEO Title',
            'seo_keywords'=>'SEO Keywords',
            'seo_description'=>'SEO Descriotion',
            'markdown'=>'Markdown文本',
            'ip_int'=>'IP',
        );
    }

    public function filters(){
        return array(
            'post_id'=>'intval',
            'seo_title'=>'trim',
            'seo_keywords'=>'trim',
            'seo_description'=>'trim',
            'markdown'=>'',
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