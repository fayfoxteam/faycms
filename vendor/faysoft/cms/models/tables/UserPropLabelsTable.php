<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 用户属性名称别名
 * 
 * @property int $id Id
 * @property int $relation_id 用户ID
 * @property int $prop_id 属性ID
 * @property string $title 属性名称别名
 */
class UserPropLabelsTable extends Table{
    protected $_name = 'user_prop_labels';
    
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
            array(array('title'), 'string', array('max'=>50)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'relation_id'=>'用户ID',
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