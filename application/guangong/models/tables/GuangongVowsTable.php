<?php
namespace guangong\models\tables;

use fay\core\db\Table;

/**
 * 预定义誓词
 *
 * @property int $id Id
 * @property string $content 誓词
 * @property int $sort 排序值
 * @property int $enabled 是否启用
 */
class GuangongVowsTable extends Table{
    protected $_name = 'guangong_vows';
    
    /**
     * @param string $class_name
     * @return GuangongVowsTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('enabled'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
            array(array('content'), 'string', array('max'=>255)),
        );
    }
    
    public function labels(){
        return array(
            'id'=>'Id',
            'content'=>'誓词',
            'sort'=>'排序值',
            'enabled'=>'是否启用',
        );
    }
    
    public function filters(){
        return array(
            'id'=>'intval',
            'content'=>'trim',
            'sort'=>'intval',
            'enabled'=>'intval',
        );
    }
}