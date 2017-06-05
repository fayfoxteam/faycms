<?php
namespace faywiki\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 文档历史版本存档
 * 
 * @property int $id Id
 * @property int $doc_id 文档ID
 * @property int $user_id 用户ID
 * @property string $title 标题
 * @property string $abstract 正文
 * @property int $thumbnail 缩略图
 * @property int $create_time 创建时间
 * @property int $ip_int IP
 */
class WikiDocHistoriesTable extends Table{
    protected $_name = 'wiki_doc_histories';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id', 'user_id', 'thumbnail'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('doc_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('title'), 'string', array('max'=>500)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'doc_id'=>'文档ID',
            'user_id'=>'用户ID',
            'title'=>'标题',
            'abstract'=>'正文',
            'thumbnail'=>'缩略图',
            'create_time'=>'创建时间',
            'ip_int'=>'IP',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'doc_id'=>'intval',
            'user_id'=>'intval',
            'title'=>'trim',
            'abstract'=>'',
            'thumbnail'=>'intval',
        );
    }
}