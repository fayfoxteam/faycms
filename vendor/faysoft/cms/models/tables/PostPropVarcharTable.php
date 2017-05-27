<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 文章自定义属性-varchar
 * 
 * @property int $id Id
 * @property int $refer 文章ID
 * @property int $prop_id 属性ID
 * @property string $content 属性值
 */
class PostPropVarcharTable extends Table{
    protected $_name = 'post_prop_varchar';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id', 'relation_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('prop_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('content'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'relation_id'=>'文章ID',
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