<?php
namespace fay\models\tables;

use fay\core\db\Table;

class KeywordsTable extends Table{
    protected $_name = 'keywords';
    
    /**
     * @param string $class_name
     * @return KeywordsTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('keyword'), 'string', array('max'=>50)),
            array(array('link'), 'string', array('max'=>500)),
            
            array(array('link', 'keyword'), 'required'),
            array('link', 'url'),
            array('keyword', 'unique', array('table'=>'keywords', 'field'=>'keyword', 'except'=>'id', 'ajax'=>array('cms/admin/keyword/is-keyword-not-exist'))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'keyword'=>'关键词',
            'link'=>'链接地址',
        );
    }

    public function filters(){
        return array(
            'keyword'=>'trim',
            'link'=>'trim',
        );
    }
}