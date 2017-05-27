<?php
namespace faywiki\models\tables;

use fay\core\db\Table;

/**
 * 百科收藏表
 * 
 * @property int $id Id
 * @property int $user_id 用户ID
 * @property int $doc_id 文档ID
 * @property int $create_time 收藏时间
 * @property int $ip_int IP
 * @property int $sockpuppet 马甲信息
 * @property string $trackid 追踪ID
 */
class WikiDocFavoritesTable extends Table{
    protected $_name = 'wiki_doc_favorites';
    
    /**
     * @param string $class_name
     * @return WikiDocFavoritesTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id', 'user_id', 'sockpuppet'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('doc_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('trackid'), 'string', array('max'=>50)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'user_id'=>'用户ID',
            'doc_id'=>'文档ID',
            'create_time'=>'收藏时间',
            'ip_int'=>'IP',
            'sockpuppet'=>'马甲信息',
            'trackid'=>'追踪ID',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'user_id'=>'intval',
            'doc_id'=>'intval',
            'sockpuppet'=>'intval',
            'trackid'=>'trim',
        );
    }
}