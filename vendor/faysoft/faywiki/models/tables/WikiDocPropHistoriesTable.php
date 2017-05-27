<?php
namespace faywiki\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 文档属性历史
 * 
 * @property int $id Id
 * @property int $history_id 历史记录id
 * @property int $doc_id 文档ID
 * @property int $prop_id 属性ID
 * @property string $prop_label 属性键
 * @property string $prop_content 属性值
 */
class WikiDocPropHistoriesTable extends Table{
    protected $_name = 'wiki_doc_prop_histories';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('history_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('doc_id', 'prop_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('prop_label'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'history_id'=>'历史记录id',
            'doc_id'=>'文档ID',
            'prop_id'=>'属性ID',
            'prop_label'=>'属性键',
            'prop_content'=>'属性值',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'history_id'=>'intval',
            'doc_id'=>'intval',
            'prop_id'=>'intval',
            'prop_label'=>'trim',
            'prop_content'=>'',
        );
    }
}