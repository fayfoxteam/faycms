<?php
namespace apidoc\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 公共请求参数
 * 
 * @property int $id Id
 * @property string $name 名称
 * @property int $required 是否必须
 * @property int $type 参数类型
 * @property string $sample 示例值
 * @property string $description 描述
 * @property string $since 自从
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class ApidocCommonInputsTable extends Table{
    protected $_name = 'apidoc_common_inputs';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('name'), 'string', array('max'=>50)),
            array(array('sample'), 'string', array('max'=>255)),
            array(array('description'), 'string', array('max'=>500)),
            array(array('since'), 'string', array('max'=>30)),

            array('type', 'range', array('range'=>array(
                InputsTable::TYPE_STRING, InputsTable::TYPE_NUMBER, InputsTable::TYPE_FILE, InputsTable::TYPE_NUMBER_SET
            ))),
            array(array('required'), 'range', array('range'=>array('0', '1'))),
            array(array('name', 'required', 'type'), 'required')
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'名称',
            'required'=>'是否必须',
            'type'=>'参数类型',
            'sample'=>'示例值',
            'description'=>'描述',
            'since'=>'自从',
            'create_time'=>'创建时间',
            'update_time'=>'更新时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'required'=>'intval',
            'type'=>'intval',
            'sample'=>'trim',
            'description'=>'trim',
            'since'=>'trim',
        );
    }

    public function getNotWritableFields($scene){
        switch($scene){
            case 'insert':
                return array('id');
                break;
            case 'update':
            default:
                return array(
                    'id', 'create_time', 'sort'
                );
        }
    }
}