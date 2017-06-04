<?php
namespace apidoc\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 错误码
 * 
 * @property int $id Id
 * @property string $code 错误码
 * @property string $description 错误描述
 * @property string $solution 解决方案
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class ApidocErrorCodesTable extends Table{
    protected $_name = 'apidoc_error_codes';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('code'), 'string', array('max'=>50)),
            array(array('description', 'solution'), 'string', array('max'=>500)),
            
            array('code', 'required'),
            array('code', 'unique', array('table'=>$this->getTableName(), 'field'=>'code', 'except'=>'id', 'ajax'=>array('apidoc/admin/error-code/is-error-code-not-exist'))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'code'=>'错误码',
            'description'=>'错误描述',
            'solution'=>'解决方案',
            'create_time'=>'创建时间',
            'update_time'=>'更新时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'code'=>'trim',
            'description'=>'trim',
            'solution'=>'trim',
        );
    }

    public function getNotWritableFields($scene){
        switch($scene){
            case 'update':
                return array(
                    'id', 'create_time'
                );
                break;
            case 'insert':
            default:
                return array('id');
                break;
        }
    }
}