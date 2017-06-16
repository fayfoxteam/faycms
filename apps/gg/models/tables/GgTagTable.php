<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 标签表
 * 
 * @property int $id Id
 * @property string $tag_name 标签名称
 */
class GgTagTable extends Table{
    protected $_name = 'gg_tag';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('tag_name'), 'string', array('max'=>10)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'tag_name'=>'标签名称',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'tag_name'=>'trim',
        );
    }
}