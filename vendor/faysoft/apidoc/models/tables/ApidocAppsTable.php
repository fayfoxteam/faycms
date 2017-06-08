<?php
namespace apidoc\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * api应用信息
 *
 * @property int $id Id
 * @property string $name 应用名称
 * @property string $description 应用描述
 * @property int $need_login 仅登录用户可见
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class ApidocAppsTable extends Table{
    protected $_name = 'apidoc_apps';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('name'), 'string', array('max'=>30)),
            array(array('description'), 'string', array('max'=>500)),
            
            array('need_login', 'range', array('range'=>array('0', '1'))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'应用名称',
            'description'=>'应用描述',
            'need_login'=>'仅登录用户可见',
            'create_time'=>'创建时间',
            'update_time'=>'更新时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'description'=>'trim',
            'need_login'=>'intval',
        );
    }
}