<?php
namespace apidoc\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Apidoc model props table model
 * 
 * @property int $id Id
 * @property int $model_id 数据模型ID
 * @property int $is_array 是否是数组
 * @property string $name 属性名称
 * @property int $type 类型
 * @property string $sample 示例值
 * @property string $description 描述
 * @property string $since 自从
 * @property int $sort 排序值
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class ApidocModelPropsTable extends Table{
    protected $_name = 'apidoc_model_props';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('model_id', 'type'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
            array(array('name'), 'string', array('max'=>50)),
            array(array('since'), 'string', array('max'=>30)),
            array(array('is_array'), 'range', array('range'=>array(0, 1))),
            
            array(array('name'), 'required'),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'model_id'=>'数据模型ID',
            'is_array'=>'是否是数组',
            'name'=>'属性名称',
            'type'=>'类型',
            'sample'=>'示例值',
            'description'=>'描述',
            'since'=>'自从',
            'sort'=>'排序值',
            'create_time'=>'创建时间',
            'update_time'=>'更新时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'model_id'=>'intval',
            'is_array'=>'intval',
            'name'=>'trim',
            'type'=>'intval',
            'sample'=>'',
            'description'=>'',
            'since'=>'trim',
            'sort'=>'intval',
        );
    }
    
    public function getPublicFields(){
        return $this->getFields(array('create_time', 'update_time'));
    }
}