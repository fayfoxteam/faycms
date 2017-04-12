<?php
namespace fayoauth\models\tables;

use fay\core\db\Table;

/**
 * 第三方登录方式
 * 
 * @property int $id Id
 * @property string $name 名称
 * @property string $description 描述
 * @property string $code 登录方式编码
 * @property string $app_id 第三方应用ID
 * @property string $app_secret App Secret
 * @property int $enabled 是否启用
 */
class OauthAppsTable extends Table{
    /**
     * @var array 登录方式编码
     */
    public static $codes = array(
        'weixin'=>'微信登录'
    );
    
    protected $_name = 'oauth_apps';
    
    /**
     * @param string $class_name
     * @return OauthAppsTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('name'), 'string', array('max'=>30)),
            array(array('description'), 'string', array('max'=>100)),
            array(array('code'), 'string', array('max'=>20)),
            array(array('app_id', 'app_secret'), 'string', array('max'=>50)),
            
            array(array('enabled'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'名称',
            'description'=>'描述',
            'code'=>'登录方式编码',
            'app_id'=>'第三方应用ID',
            'app_secret'=>'App Secret',
            'enabled'=>'是否启用',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'description'=>'trim',
            'code'=>'trim',
            'app_id'=>'trim',
            'app_secret'=>'trim',
            'enabled'=>'intval',
        );
    }
}