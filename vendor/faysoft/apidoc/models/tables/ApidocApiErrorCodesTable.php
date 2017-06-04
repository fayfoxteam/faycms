<?php
namespace apidoc\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * api错误码
 *
 * @property int $id Id
 * @property int $api_id Api ID
 * @property string $code 错误码
 * @property string $description 错误描述
 * @property string $solution 解决方案
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class ApidocApiErrorCodesTable extends Table{
    protected $_name = 'apidoc_api_error_codes';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('api_id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('code'), 'string', array('max'=>50)),
            array(array('description', 'solution'), 'string', array('max'=>500)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'api_id'=>'Api ID',
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
            'api_id'=>'intval',
            'code'=>'trim',
            'description'=>'trim',
            'solution'=>'trim',
        );
    }
}