<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

class KeywordsTable extends Table{
    protected $_name = 'keywords';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
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