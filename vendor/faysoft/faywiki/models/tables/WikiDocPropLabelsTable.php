<?php
namespace faywiki\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Wiki doc prop labels table model
 * 
 * @property int $id Id
 * @property int $relation_id 文档ID
 * @property int $prop_id 属性ID
 * @property string $title 属性名称别名
 */
class WikiDocPropLabelsTable extends Table{
    protected $_name = 'wiki_doc_prop_labels';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('relation_id', 'prop_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('title'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'relation_id'=>'文档ID',
            'prop_id'=>'属性ID',
            'title'=>'属性名称别名',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'relation_id'=>'intval',
            'prop_id'=>'intval',
            'title'=>'trim',
        );
    }
}