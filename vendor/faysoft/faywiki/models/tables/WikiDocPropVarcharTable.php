<?php
namespace faywiki\models\tables;

use fay\core\db\Table;

/**
 * 文档自定义属性-varchar
 * 
 * @property int $id Id
 * @property int $relation_id 文档ID
 * @property int $prop_id 属性ID
 * @property string $content 属性值
 */
class WikiDocPropVarcharTable extends Table{
    protected $_name = 'wiki_doc_prop_varchar';
    
    /**
     * @param string $class_name
     * @return WikiDocPropVarcharTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('relation_id', 'prop_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('content'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'relation_id'=>'文档ID',
            'prop_id'=>'属性ID',
            'content'=>'属性值',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'relation_id'=>'intval',
            'prop_id'=>'intval',
            'content'=>'trim',
        );
    }
}