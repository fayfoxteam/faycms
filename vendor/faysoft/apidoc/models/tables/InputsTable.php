<?php
namespace apidoc\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Apidoc inputs table model
 * 
 * @property int $id Id
 * @property int $api_id 接口ID
 * @property string $name 名称
 * @property int $required 是否必须
 * @property int $type 参数类型
 * @property string $sample 示例值
 * @property string $description 描述
 * @property string $since 自从
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class InputsTable extends Table{
/**
     * 类型 - 字符串
     */
    const TYPE_STRING = 1;
    
    /**
     * 类型 - 数字
     */
    const TYPE_NUMBER = 2;

    /**
     * 类型 - 数字
     */
    const TYPE_FILE = 3;

    /**
     * 类型 - 逗号分割的数字集合
     */
    const TYPE_NUMBER_SET = 3;

    /**
     * 类型 - 逗号分割的字符串集合
     */
    const TYPE_STRING_SET = 4;
    
    protected $_name = 'apidoc_inputs';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('api_id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('required', 'type'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('name'), 'string', array('max'=>50)),
            array(array('since'), 'string', array('max'=>30)),
            
            array('type', 'range', array('range'=>array(
                self::TYPE_STRING, self::TYPE_NUMBER, self::TYPE_FILE, self::TYPE_NUMBER_SET
            ))),
            array(array('name', 'required', 'type'), 'required')
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'api_id'=>'接口ID',
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
            'api_id'=>'intval',
            'name'=>'trim',
            'required'=>'intval',
            'type'=>'intval',
            'sample'=>'',
            'description'=>'',
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
                    'id', 'api_id', 'create_time'
                );
        }
    }
    
    /**
     * 返回类型-类型描述数组
     */
    public static function getTypes(){
        return array(
            self::TYPE_NUMBER => '数字',
            self::TYPE_STRING => '字符串',
            self::TYPE_FILE => '文件',
            self::TYPE_NUMBER_SET => '数字集合',
            self::TYPE_STRING_SET => '字符串集合',
        );
    }
}