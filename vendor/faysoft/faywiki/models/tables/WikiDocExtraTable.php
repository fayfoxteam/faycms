<?php
namespace faywiki\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 文档扩展表
 * 
 * @property int $doc_id 文档ID
 * @property string $seo_title SEO Title
 * @property string $seo_keywords SEO Keywords
 * @property string $seo_description SEO Description
 * @property int $ip_int IP
 */
class WikiDocExtraTable extends Table{
    protected $_name = 'wiki_doc_extra';
    protected $_primary = 'doc_id';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('doc_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('seo_title', 'seo_keywords'), 'string', array('max'=>255)),
            array(array('seo_description'), 'string', array('max'=>500)),
        );
    }

    public function labels(){
        return array(
            'doc_id'=>'文档ID',
            'seo_title'=>'SEO Title',
            'seo_keywords'=>'SEO Keywords',
            'seo_description'=>'SEO Description',
            'ip_int'=>'IP',
        );
    }

    public function filters(){
        return array(
            'doc_id'=>'intval',
            'seo_title'=>'trim',
            'seo_keywords'=>'trim',
            'seo_description'=>'trim',
        );
    }
}