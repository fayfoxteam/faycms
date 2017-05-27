<?php
namespace faywiki\models\tables;

use fay\core\db\Table;

/**
 * 百科文档
 *
 * @property int $id Id
 * @property int $user_id 用户ID
 * @property int $cat_id 分类ID
 * @property string $title 标题
 * @property string $abstract 摘要
 * @property int $thumbnail 缩略图
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $delete_time 删除时间
 * @property int $write_lock 编辑锁
 */
class WikiDocsTable extends Table{
    protected $_name = 'wiki_docs';

    /**
     * @param string $class_name
     * @return WikiDocsTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }

    public function rules(){
        return array(
            array(array('user_id', 'thumbnail'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('id'), 'int', array('min'=>-8388608, 'max'=>8388607)),
            array(array('cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('write_lock'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('title'), 'string', array('max'=>100)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'user_id'=>'用户ID',
            'cat_id'=>'分类ID',
            'title'=>'标题',
            'abstract'=>'摘要',
            'thumbnail'=>'缩略图',
            'create_time'=>'创建时间',
            'update_time'=>'更新时间',
            'delete_time'=>'删除时间',
            'write_lock'=>'编辑锁',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'user_id'=>'intval',
            'cat_id'=>'intval',
            'title'=>'trim',
            'abstract'=>'',
            'thumbnail'=>'intval',
            'write_lock'=>'intval',
        );
    }
}