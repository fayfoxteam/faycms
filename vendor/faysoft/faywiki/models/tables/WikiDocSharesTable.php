<?php
namespace faywiki\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 文档分享记录
 * 
 * @property int $id Id
 * @property int $doc_id 文档ID
 * @property int $user_id 用户ID
 * @property string $type 分享方式
 * @property int $create_time 创建时间
 * @property int $ip_int IP
 * @property int $sockpuppet 马甲信息
 * @property string $trackid 追踪ID
 */
class WikiDocSharesTable extends Table{
    protected $_name = 'wiki_doc_shares';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id', 'user_id', 'sockpuppet'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('doc_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('type'), 'string', array('max'=>30)),
            array(array('trackid'), 'string', array('max'=>50)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'doc_id'=>'文档ID',
            'user_id'=>'用户ID',
            'type'=>'分享方式',
            'create_time'=>'创建时间',
            'ip_int'=>'IP',
            'sockpuppet'=>'马甲信息',
            'trackid'=>'追踪ID',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'doc_id'=>'intval',
            'user_id'=>'intval',
            'type'=>'trim',
            'sockpuppet'=>'intval',
            'trackid'=>'trim',
        );
    }
}