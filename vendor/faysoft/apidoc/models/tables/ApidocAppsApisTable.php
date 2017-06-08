<?php
namespace apidoc\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 应用-接口关联关系
 * 
 * @property int $id Id
 * @property int $app_id App Id
 * @property int $api_id Api Id
 */
class ApidocAppsApisTable extends Table{
    protected $_name = 'apidoc_apps_apis';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('app_id', 'api_id'), 'int', array('min'=>0, 'max'=>65535)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'app_id'=>'App Id',
            'api_id'=>'Api Id',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'app_id'=>'intval',
            'api_id'=>'intval',
        );
    }
}