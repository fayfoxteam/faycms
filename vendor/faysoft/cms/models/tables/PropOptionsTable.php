<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 自定义属性选项列表
 * 
 * @property int $id Id
 * @property int $prop_id 属性ID
 * @property string $title 选项标题
 * @property int $default 默认选中
 * @property int $delete_time 删除时间
 * @property int $sort 排序值
 */
class PropOptionsTable extends Table{
    protected $_name = 'prop_options';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('prop_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('default'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
            array(array('title'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'prop_id'=>'属性ID',
            'title'=>'选项标题',
            'default'=>'默认选中',
            'delete_time'=>'删除时间',
            'sort'=>'排序值',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'prop_id'=>'intval',
            'title'=>'trim',
            'default'=>'intval',
            'sort'=>'intval',
        );
    }
}